<?php

// Dans la structure du projet, la classe est dans Personnage
namespace App\Model;

class Vampire extends Personnage
{
    public function __construct(
        string $nom,
        int $PV,
        int $PVMax,
        int $force,
        int $facesDe = 8,
        int $chance = 70,
        int $money = 100,
        string $avatar = 'vampire.jpg',
        int $XP = 0,
        int $level = 0,
        ?int $id = null
    ) {
        // Appel du constructeur parent avec les bons paramètres
        parent::__construct($nom, $PV, $PVMax, $force, $facesDe, $chance, $money, $avatar, $XP, $level, $id);
        $this->classe = "Vampire";
    }

    // Dynamique d'attaque différente pour les vampires : héritage

    // On commence à typer les propriétés pour éviter les erreurs
    public function attaquer(Personnage $cible)
    {
        // On lance le dé
        $scoreDe = rand(1, $this->getFacesDe());

        $resultat = "{$this->getTitle()} lance son dé à {$this->getFacesDe()} faces et obtient $scoreDe\n";

        // On ramène à une chiffre entre 0 et 1
        $factDe = $scoreDe / $this->getFacesDe();
        // On ajoute la chance
        $factChance = $this->getChance() / 100;
        // On fait une moyenne entre le dé et la chance
        $chanceFinale = ($factDe + $factChance) / 2;

        // Si le calcul est inférieur à 0.5, on rate l'attaque
        $success = $chanceFinale > 0.5;

        if (!$success) {
            $resultat .= "{$this->getTitle()} rate son attaque !\n";
            return $resultat;
        } else {
            $resultat .= "{$this->getTitle()} attaque {$cible->getTitle()}! GRAOUUUU !\n";
            $resultat .= "{$cible->getTitle()} perd {$this->getForce()} PV !\n";
            $cible->setPV($cible->getPV() - $this->getForce());

            // On rajoute un vampirisation, pour la classe Vampire
            $vampirisation = round(intval($this->getForce() / 3));
            $this->setPV($this->getPV() + $vampirisation);
            $resultat .= "{$this->getTitle()} récupère $vampirisation PV !\n";
        }

        return $resultat;
    }
}
