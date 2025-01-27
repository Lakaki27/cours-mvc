<?php

namespace App\Repository;

use App\Model\Database;
use App\Model\Bagarre;
use App\Model\Personnage;
use PDO;

class BagarreRepository
{
    private PDO $pdo;
    private PersonnageRepository $pr;

    public const ALL_MOVES = [
        "attack",
        "buff",
        "flee",
        "wait"
    ];
    /**
     * Constructeur : Initialise la connexion à la base de données.
     */
    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->pr = new PersonnageRepository();
    }

    private function createInstance(array $data)
    {
        return new Bagarre(
            $data["id"],
            $this->pr->getById($data["id_a"]),
            $this->pr->getById($data["id_b"]),
            $data["hp_a"],
            $data["hp_b"],
            $data["str_a"],
            $data["str_b"],
            $data["turn"]
        );
    }

    public function getByCharIds(int $id_a, int $id_b): ?Bagarre
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bagarres WHERE ((id_a = :id_a) AND (id_b = :id_b)) OR ((id_a = :id_b) AND (id_b = :id_a))");
        $stmt->execute([':id_a' => $id_a, ':id_b' => $id_b]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createInstance($row) : null;
    }

    public function getById(int $id): ?Personnage
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bagarres WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createInstance($row) : null;
    }

    public function getAllBagarres()
    {
        $stmt = $this->pdo->query("SELECT * FROM bagarres");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->createInstance($row), $results);
    }

    public function add(array $personnages)
    {
        $personnageA = $personnages[0];
        $personnageB = $personnages[1];

        $stmt = $this->pdo->prepare("
            INSERT INTO bagarres (id_a, id_b, hp_a, hp_b, str_a, str_b, turn)
            VALUES (:id_a, :id_b, :hp_a, :hp_b, :str_a, :str_b, :turn)
        ");

        $stmt->execute([
            ":id_a" => $personnageA->getId(),
            ":id_b" => $personnageB->getId(),
            ":hp_a" => $personnageA->getPV(),
            ":hp_b" => $personnageB->getPV(),
            ":str_a" => $personnageA->getForce(),
            ":str_b" => $personnageB->getForce(),
            ":turn" => 0
        ]);

        $bagarre = $this->getByCharIds($personnageA->getId(), $personnageB->getId());

        return $bagarre;
    }

    public function delete(Bagarre $bagarre): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM bagarres WHERE id = :id");
        $stmt->execute([':id' => $bagarre->getId()]);
    }

    public function update(Bagarre $bagarre): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE bagarres
            SET hp_a = :hp_a,
                hp_b = :hp_b,
                str_a = :str_a,
                str_b = :str_b,
                turn = :turn
            WHERE id = :id
        ");

        $stmt->execute([
            ':hp_a' => $bagarre->getPersonnageA()->getPV(),
            ':hp_b' => $bagarre->getPersonnageB()->getPV(),
            ':str_a' => $bagarre->getPersonnageA()->getForce(),
            ':str_b' => $bagarre->getPersonnageB()->getForce(),
            ':turn' => $bagarre->getTurn(),
            ':id' => $bagarre->getId()
        ]);
    }
}
