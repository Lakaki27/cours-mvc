<?php

namespace App\Repository;

use App\Model\Database;
use PDO;
use App\Model\Voleur;
use App\Model\Vampire;
use App\Model\Personnage;

class PersonnageRepository
{
    private PDO $pdo;

    /**
     * Constructeur : Initialise la connexion à la base de données.
     */
    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Crée dynamiquement une instance de personnage ou sous-classe (comme Vampire).
     * 
     * Ici on peut donc tirer parti du fait que la classe Vampire hérite de Personnage pour instancier dynamiquement la bonne classe.
     * C'est une forme de "factory" qui permet de créer des instances de classes dérivées sans avoir à les connaître à l'avance.
     *
     * @param array $row Les données récupérées depuis la base de données.
     * @return Personnage Une instance de Personnage ou de ses sous-classes.
     */
    private function createInstance(array $row): Personnage
    {
        return match ($row['class']) {
            'Vampire' => new Vampire(
                $row['name'],                      // Nom
                intval($row['PV']),                // PV
                intval($row['PVMax']),             // PVMax
                intval($row['str']),               // Force
                intval($row['facesDe']),           // FacesDe
                intval($row['chance']),            // Chance
                intval($row['mny']),               // Money
                $row['avatar'],                    // Avatar
                intval($row['XP']),                // XP
                intval($row['level']),             // Level
                intval($row['id'])                 // ID
            ),
            'Voleur' => new Voleur(
                $row['name'],                      // Nom
                intval($row['PV']),                // PV
                intval($row['PVMax']),             // PVMax
                intval($row['str']),               // Force
                intval($row['facesDe']),           // FacesDe
                intval($row['chance']),            // Chance
                intval($row['mny']),               // Money
                $row['avatar'],                    // Avatar
                intval($row['XP']),                // XP
                intval($row['level']),             // Level
                intval($row['id'])                 // ID
            ),
            default => new Personnage(
                $row['name'],                      // Nom
                intval($row['PV']),                // PV
                intval($row['PVMax']),             // PVMax
                intval($row['str']),               // Force
                intval($row['facesDe']),           // FacesDe
                intval($row['chance']),            // Chance
                intval($row['mny']),               // Money
                $row['avatar'],                    // Avatar
                intval($row['XP']),                // XP
                intval($row['level']),             // Level
                intval($row['id'])                 // ID
            )
        };
    }

    /**
     * Récupère tous les personnages de la base.
     *
     * @return Personnage[] Un tableau d'instances de Personnage ou sous-classes.
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM characters");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->createInstance($row), $results);
    }

    /**
     * Récupère un personnage par son ID.
     *
     * @param int $id L'ID du personnage à récupérer.
     * @return Personnage|null L'instance du personnage ou null si non trouvé.
     */
    public function getById(int $id): ?Personnage
    {
        $stmt = $this->pdo->prepare("SELECT * FROM characters WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createInstance($row) : null;
    }

    /**
     * Ajoute un personnage dans la base et retourne l'instance avec son ID mis à jour.
     *
     * @param Personnage $character L'instance du personnage à ajouter.
     * @return Personnage L'instance du personnage ajouté avec son ID.
     */
    public function add(Personnage $character): Personnage
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO characters (name, PV, PVMax, str, facesDe, chance, XP, level, avatar, class, mny)
            VALUES (:name, :PV, :PVMax, :force, :facesDe, :chance, :XP, :level, :avatar, :class, :money)
        ");

        $stmt->execute([
            ':name' => $character->getNom(),
            ':PV' => $character->getPV(),
            ':PVMax' => $character->getPVMax(),
            ':force' => $character->getForce(),
            ':facesDe' => $character->getFacesDe(),
            ':chance' => $character->getChance(),
            ':XP' => 0,
            ':level' => 0,
            ':avatar' => substr($character->getAvatar(), 5),
            ':class' => $character->getClasse(),
            ':money' => $character->getMoney()
        ]);

        // Mettre à jour l'ID de l'instance après l'insertion
        $character->setId((int) $this->pdo->lastInsertId());

        return $character;
    }

    /**
     * Supprime un personnage de la base par son ID.
     *
     * @param int $id L'ID du personnage à supprimer.
     * @return void
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM characters WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function update(Personnage $character): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE characters
            SET name = :name,
                PV = :PV,
                PVMax = :PVMax,
                str = :force,
                facesDe = :facesDe,
                chance = :chance,
                XP = :XP,
                level = :level,
                avatar = :avatar,
                class = :class,
                mny = :mny
            WHERE id = :id
        ");

        $stmt->execute([
            ':name' => $character->getNom(),
            ':PV' => $character->getPV(),
            ':PVMax' => $character->getPVMax(),
            ':force' => $character->getForce(),
            ':facesDe' => $character->getFacesDe(),
            ':chance' => $character->getChance(),
            ':XP' => $character->getXP(),
            ':level' => $character->getLevel(),
            ':avatar' => $character->getAvatar(),
            ':class' => $character->getClasse(),
            ':mny' => $character->getMoney(),
            ':id' => $character->getId()
        ]);
    }

    public function updateAfterVictory(Personnage $character): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE characters
            SET XP = :XP,
                level = :level,
                mny = :mny
            WHERE id = :id
        ");

        $stmt->execute([
            ':XP' => $character->getXP(),
            ':level' => $character->getLevel(),
            ':mny' => $character->getMoney(),
            ':id' => $character->getId()
        ]);
    }

    public function updateLevelAndXP(Personnage $character): void
    {
        $stmt = $this->pdo->prepare("UPDATE characters SET XP = :xp, level = :level WHERE id = :id");
        $stmt->execute([':id' => $character->getId(), ':XP' => $character->getXP(), ':level' => $character->getLevel()]);
    }
}
