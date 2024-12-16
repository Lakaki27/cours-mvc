<?php

namespace App\Model;

use PDO;
use PDOException;

/**
 * Classe Database
 * Cette classe gère la connexion à la base de données en utilisant PDO.
 * Elle est conçue pour être utilisée comme une couche d'accès à la base dans un projet MVC.
 */
class Database
{
    private static ?PDO $pdo = null;

    /**
     * Retourne une connexion PDO à la base de données.
     * Utilise un singleton pour éviter des connexions multiples inutiles.
     * (Cherchez ce qu'est un singleton)
     *
     * @return PDO La connexion PDO active.
     */
    public static function getConnection(): PDO
    {
        // Vérifie si une connexion PDO existe déjà
        if (self::$pdo === null) {
            try {
                // Informations de connexion
                $host = 'mysql'; // Nom du serveur MySQL
                $port = 3306;
                $dbname = 'mydatabase'; // Nom de la base de données
                $user = 'user'; // Utilisateur mysql
                $password = 'password'; // Mot de passe mysql

                // DSN MySQL
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

                // Création de l'objet PDO
                self::$pdo = new PDO($dsn, $user, $password);

                // Configuration des attributs PDO
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Exceptions pour les erreurs SQL
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Récupération en tableau associatif
            } catch (PDOException $e) {
                // Gestion des erreurs de connexion
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return self::$pdo; // Retourne l'objet PDO existant ou nouvellement créé
    }
}
