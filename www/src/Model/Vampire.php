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
        ?int $id = null
    ) {
        // Appel du constructeur parent avec les bons paramètres
        parent::__construct($nom, $PV, $PVMax, $force, $facesDe, $chance, $money, $avatar, $XP, $id);
        $this->classe = "Vampire";
    }

    // Dynamique d'attaque différente pour les vampires : héritage

    // On commence à typer les propriétés pour éviter les erreurs
    public function attaquer(Personnage $cible)
    {
        // On lance le dé
        $scoreDe = rand(1, $this->facesDe);

        $resultat = "$this->nom lance son dé à $this->facesDe faces et obtient $scoreDe\n";

        // On ramène à une chiffre entre 0 et 1
        $factDe = $scoreDe / $this->facesDe;
        // On ajoute la chance
        $factChance = $this->chance / 100;
        // On fait une moyenne entre le dé et la chance
        $chanceFinale = ($factDe + $factChance) / 2;

        // Si le calcul est inférieur à 0.5, on rate l'attaque
        $success = $chanceFinale > 0.5;

        if (!$success) {
            $resultat .= "$this->nom rate son attaque !\n";
            return $resultat;
        } else {
            $resultat .= "$this->nom attaque $cible->nom! GRAOUUUU !\n";
            $resultat .= "$cible->nom perd $this->force PV !\n";
            $cible->PV -= $this->force;

            // On rajoute un vampirisation, pour la classe Vampire
            $vampirisation = intval($this->force / 10);
            $this->PV += $vampirisation;
            $resultat .= "$cible->nom a maintenant $cible->PV PV restants et $this->nom récupère $vampirisation PV !\n";
        }

        return $resultat;
    }
}
