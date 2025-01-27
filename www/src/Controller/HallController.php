<?php

namespace App\Controller;

use App\Model\Bagarre;
use App\MoteurDeRendu;
use App\Repository\BagarreRepository;
use App\Repository\PersonnageRepository;

class HallController
{
    private ?PersonnageRepository $pr = null;
    private ?BagarreRepository $bagarreRepo = null;

    public function __construct()
    {
        $this->pr = new PersonnageRepository;
        $this->bagarreRepo = new BagarreRepository;
    }

    public function afficherHall()
    {
        $bagarres = $this->bagarreRepo->getAllBagarres();

        $personnages = $this->pr->getAll();

        $moteur = new MoteurDeRendu();

        $content = $moteur->render('hallView', ["personnages" => $personnages, "bagarres" => $bagarres]);

        // Rendre l'index avec le contenu généré
        echo $moteur->render('indexView', [
            'contenu' => $content,
            'header' => $moteur->render('headerView'),
            'footer' => $moteur->render('footerView')
        ]);
    }
}
