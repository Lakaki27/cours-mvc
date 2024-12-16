<?php

namespace App\Repository;

use App\Model\Personnage;
use App\Model\Vampire;
use App\Model\Database;
use PDO;

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
            INSERT INTO characters (name, PV, PVMax, str, facesDe, chance, XP, avatar, class, mny)
            VALUES (:name, :PV, :PVMax, :force, :facesDe, :chance, :XP, :avatar, :class, :money)
        ");

        $stmt->execute([
            ':name' => $character->nom,
            ':PV' => $character->PV,
            ':PVMax' => $character->PVMax,
            ':force' => $character->force,
            ':facesDe' => $character->facesDe,
            ':chance' => $character->chance,
            ':XP' => 0,
            ':avatar' => $character->avatar,
            ':class' => $character->getClasse(),
            ':money' => $character->money
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
        // Implémenter
    }

    public function update(Personnage $character): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE characters
            SET name = :name,
                PV = :PV,
                PVMax = :PVMax,
                str = :str,
                facesDe = :facesDe,
                chance = :chance,
                XP = :XP,
                avatar = :avatar,
                class = :class,
                mny = :mny
            WHERE id = :id
        ");

        $stmt->execute([
            ':name' => $character->getNom(),
            ':PV' => $character->PV,
            ':PVMax' => $character->PVMax,
            ':str' => $character->force,
            ':facesDe' => $character->facesDe,
            ':chance' => $character->chance,
            ':XP' => $character->XP,
            ':avatar' => $character->avatar,
            ':class' => $character->getClasse(),
            ':mny' => $character->money,
            ':id' => $character->getId()
        ]);
    }
}
