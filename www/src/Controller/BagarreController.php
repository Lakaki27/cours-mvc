<?php

namespace App\Controller;

use App\Model\Bagarre;
use App\MoteurDeRendu;
use App\Repository\BagarreRepository;
use App\Repository\PersonnageRepository;

class BagarreController
{
    private ?PersonnageRepository $pr = null;
    private ?BagarreRepository $bagarreRepo = null;

    public function __construct()
    {
        $this->pr = new PersonnageRepository;
        $this->bagarreRepo = new BagarreRepository;
    }

    private function getCharacters()
    {
        $idA = $_GET['a'] ?? null;
        $idB = $_GET['b'] ?? null;

        // Vérifie que les deux IDs sont présents
        if (!$idA || !$idB) {
            die("IDs des personnages manquants !");
            return;
        }

        // Récupère les deux personnages depuis la base
        $personnageA = $this->pr->getById((int)$idA);
        $personnageB = $this->pr->getById((int)$idB);

        // Vérifie que les personnages existent
        if (!$personnageA || !$personnageB) {
            die("L'un des personnages n'existe pas.");
            return;
        }

        return [$personnageA, $personnageB];
    }

    private function getMoves()
    {
        $moveA = $_GET['move1'] ?? null;
        $moveB = $_GET['move2'] ?? null;

        if (!$moveA || !$moveB || !in_array($moveA, $this->bagarreRepo::ALL_MOVES) || !in_array($moveB, $this->bagarreRepo::ALL_MOVES)) {
            die("Action incorrecte !");
            return;
        }

        return ["moveA" => $moveA, "moveB" => $moveB];
    }

    public function getNextTurn()
    {
        [$personnageA, $personnageB] = $this->getCharacters();

        $moves = $this->getMoves();

        $bagarre = $this->bagarreRepo->getByCharIds($personnageA->getId(), $personnageB->getId());

        if (!$bagarre) {
            $bagarre = $this->bagarreRepo->add($this->getCharacters());
        }

        if (!$bagarre) {
            die("Erreur dans la bagarre.");
            return;
        }

        return $this->oneBagarreTurn($bagarre, $moves);
    }

    public function afficherBagarre()
    {
        // Récupération des paramètres a (ID perso 1) et b (ID perso 2) depuis l'URL
        [$personnageA, $personnageB] = $this->getCharacters();

        // Utiliser le moteur de rendu
        $moteur = new MoteurDeRendu();

        // Préparer les données pour la vue
        $cardGauche = $moteur->render('cardView', ['personnage' => $personnageA, "letter" => "A"]);
        $cardDroite = $moteur->render('cardView', ['personnage' => $personnageB, "letter" => "B"]);

        // Créer la vue de bagarre
        $contenu = $moteur->render('bagarreView', [
            'combattant1' => $cardGauche,
            'combattant2' => $cardDroite,
        ]);

        // Rendre l'index avec le contenu généré
        echo $moteur->render('indexView', [
            'contenu' => $contenu,
            'header' => $moteur->render('headerView'),
            'footer' => $moteur->render('footerView')
        ]);
    }

    private function doVictory(array $combattants)
    {
        $resultat = "";
        $winner = null;
        // Déterminer le vainqueur
        if (!$combattants[0]->isAlive()) {
            $winner = $combattants[1];
        } else {
            $winner = $combattants[0];
        }
        $gainedLevels = $winner->gagnerXP(17);
        $gainedLevels = $winner->gagnerMoney(35);

        $this->pr->updateAfterVictory($combattants[0]);
        $this->pr->updateAfterVictory($combattants[1]);

        $resultat .= "{$winner->getTitle()} a gagné ! (+17 XP)\n";

        $resultat .= "----------------------------------------------------------------------\n";

        $resultat .= "{$winner->getTitle()} remporte 35 pièces !\n";

        if ($gainedLevels > 0) {
            $resultat .= "{$winner->getTitle()} gagne {$gainedLevels} niveau(x) ! Il évolue au niveau {$winner->getLevel()} !\n";
        }

        $resultat .= "XP: {$winner->getXP()}/{$winner->getXPForLevelUp()}";

        return $resultat;
    }

    private function oneBagarreTurn(Bagarre $bagarre, array $moves)
    {
        $resultat = [
            "content" => "",
            "isFinished" => false,
            "PVs" => [
                "PVa" => "",
                "PVb" => "",
            ],
            "forces" => [
                "forceA" => "",
                "forceB" => "",
            ]
        ];

        $fleed = false;

        $combattants = [
            $bagarre->getPersonnageA()->setPV($bagarre->getHpA())->setForce($bagarre->getForceA()),
            $bagarre->getPersonnageB()->setPV($bagarre->getHpB())->setForce($bagarre->getForceB()),
        ];

        if ($combattants[0]->isAlive() && $combattants[1]->isAlive()) {
            $bagarre->setTurn($bagarre->getTurn() + 1);
            $resultat["content"] .= "Tour {$bagarre->getTurn()}: \n";

            // Combattant 1 attaque$
            switch ($moves["moveA"]) {
                case "attack":
                    $resultat["content"] .= $combattants[0]->attaquer($combattants[1]);
                    break;
                case "wait":
                    $resultat["content"] .= $combattants[0]->wait();
                    break;
                case "flee":
                    $resultat["content"] .= "{$combattants[0]->getNom()} prend la fuite !";
                    $fleed = true;
                    break;
            }

            // Combattant 2 attaque seulement s'il est encore en vie après l'attaque du premier
            if ($combattants[1]->isAlive() && !$fleed) {
                switch ($moves["moveB"]) {
                    case "attack":
                        $resultat["content"] .= $combattants[1]->attaquer($combattants[0]);
                        break;
                    case "wait":
                        $resultat["content"] .= $combattants[1]->wait();
                        break;
                    case "flee":
                        $resultat .= "{$combattants[1]->getNom()} prend la fuite !";
                        $fleed = true;
                        break;
                }
            }

            if (!$fleed) {
                $resultat["content"] .= "{$combattants[0]->getNom()}: {$combattants[0]->getPV()} PV, {$combattants[1]->getNom()}: {$combattants[1]->getPV()} PV\n";
                $resultat["content"] .= "\n";
            }

            $resultat["content"] .= "\n";

            $resultat["PVs"]["PVa"] = $combattants[0]->getPV();
            $resultat["PVs"]["PVb"] = $combattants[1]->getPV();
            $resultat["forces"]["forceA"] = $combattants[0]->getForce();
            $resultat["forces"]["forceB"] = $combattants[1]->getForce();

            $bagarre->setPersonnageA($combattants[0]);
            $bagarre->setPersonnageB($combattants[1]);

            $this->bagarreRepo->update($bagarre);
        } else {
            $this->bagarreRepo->delete($bagarre);
            $resultat["content"] = $this->doVictory($combattants);
            $resultat["PVs"]["PVa"] = $combattants[0]->getPV();
            $resultat["PVs"]["PVb"] = $combattants[1]->getPV();
            $resultat["forces"]["forceA"] = $combattants[0]->getForce();
            $resultat["forces"]["forceB"] = $combattants[1]->getForce();
            $resultat["isFinished"] = true;
        }

        if ($fleed) {
            $this->bagarreRepo->delete($bagarre);
            $resultat["isFinished"] = true;
        }

        return $resultat;
    }
}
