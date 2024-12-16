<?php

namespace App\Controller;

use App\MoteurDeRendu;
use App\Repository\PersonnageRepository;

class BagarreController
{
    public function afficherBagarre()
    {
        // Récupération des paramètres a (ID perso 1) et b (ID perso 2) depuis l'URL
        $idA = $_GET['a'] ?? null;
        $idB = $_GET['b'] ?? null;

        // Vérifie que les deux IDs sont présents
        if (!$idA || !$idB) {
            die("IDs des personnages manquants !");
            return;
        }

        $pr = new PersonnageRepository();

        // Récupère les deux personnages depuis la base
        $personnageA = $pr->getById((int)$idA);
        $personnageB = $pr->getById((int)$idB);

        // Vérifie que les personnages existent
        if (!$personnageA || !$personnageB) {
            die("L'un des personnages n'existe pas.");
            return;
        }
        // Utiliser le moteur de rendu
        $moteur = new MoteurDeRendu();

        // Préparer les données pour la vue
        $cardGauche = $moteur->render('cardView', ['personnage' => $personnageA]);
        $cardDroite = $moteur->render('cardView', ['personnage' => $personnageB]);
        $bagarre = $this->bagarre($personnageA, $personnageB);

        // Créer la vue de bagarre
        $contenu = $moteur->render('bagarreView', [
            'combattant1' => $cardGauche,
            'combattant2' => $cardDroite,
            'bagarre' => htmlspecialchars($bagarre)
        ]);

        // Rendre l'index avec le contenu généré
        echo $moteur->render('indexView', [
            'contenu' => $contenu,
            'header' => $moteur->render('headerView'),
            'footer' => $moteur->render('footerView')
        ]);
    }


    private function bagarre($combattant1, $combattant2)
    {
        $resultat = "";

        $tour = 0;

        $combattants = [
            $combattant1,
            $combattant2
        ];

        shuffle($combattants);

        while ($combattants[0]->isAlive() && $combattants[1]->isAlive()) {
            $tour++;
            $resultat .= "Tour $tour : \n";

            // Combattant 1 attaque
            if ($combattants[0]->isAlive()) {
                $resultat .= $combattants[0]->attaquer($combattants[1]);
            }

            // Combattant 2 attaque seulement s'il est encore en vie après l'attaque du premier
            if ($combattants[1]->isAlive()) {
                $resultat .= $combattants[1]->attaquer($combattants[0]);
            }

            $resultat .= "\n";
        }

        // Déterminer le vainqueur
        if (!$combattants[0]->isAlive()) {
            $combattants[1]->gagnerXP(10);
            $resultat .= "{$combattants[1]->getNom()} a gagné ! Il a maintenant {$combattants[1]->getXP()} XP (+10)\n";
        } else {
            $combattants[0]->gagnerXP(10);
            $resultat .= "{$combattants[0]->getNom()} a gagné ! Il a maintenant {$combattants[0]->getXP()} XP (+10)\n";
        }

        $resultat .= "----------------------------------------------------------------------\n";

        return $resultat;
    }
}
