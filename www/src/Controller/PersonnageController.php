<?php

namespace App\Controller;

use App\Model\Personnage;
use App\Repository\PersonnageRepository;
use App\MoteurDeRendu;

class PersonnageController
{
    private PersonnageRepository $repository;
    private MoteurDeRendu $moteur;

    public function __construct()
    {
        $this->repository = new PersonnageRepository();
        $this->moteur = new MoteurDeRendu();
    }

    /**
     * Affiche la liste des personnages.
     */
    public function index()
    {
        $characters = $this->repository->getAll();
        $contenu = $this->moteur->render('listView', ['characters' => $characters]);

        echo $this->moteur->render('indexView', [
            'contenu' => $contenu,
            'header' => $this->moteur->render('headerView'),
            'footer' => $this->moteur->render('footerView')
        ]);
    }

    /**
     * Affiche le formulaire pour ajouter un personnage et traite l'ajout.
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $personnage = new Personnage(
                $_POST['name'],
                $_POST['PV'],
                $_POST['force'],
                $_POST['money']
            );
            $this->repository->add($personnage);
            header('Location: /?action=index');
            exit();
        }

        require __DIR__ . '/../View/add.php';
    }

    /**
     * Supprime un personnage par son ID.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->repository->delete($id);
        header('Location: /?action=index');
        exit();
    }

    /**
     * Affiche le formulaire pour éditer/ajouter un personnage et traite la modification.
     *
     * @param int|null $id
     */
    public function createOrEdit(?int $id = null)
    {
        $repository = new PersonnageRepository();

        // Vérifie si on est en mode édition
        $character = null;
        if ($id) {
            // Si un ID est fourni, on tente de récupérer le personnage correspondant
            $character = $repository->getById($id);
            if (!$character) {
                // Si le personnage n'existe pas, on redirige vers la page d'accueil (on pourrait aussi afficher un message d'erreur)
                header("Location: /");
                exit();
            }
        }

        // Si la méthode est post, on traite le formulaire qui a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'PV' => intval($_POST['PV']),
                'PVMax' => intval($_POST['PVMax']),
                'force' => intval($_POST['force']),
                'money' => intval($_POST['money']),
                'avatar' => $_POST['avatar'],
            ];

            if ($character) {
                // Mise à jour
                $character->setNom($data['name']);
                $character->PV = $data['PV'];
                $character->PVMax = $data['PVMax'];
                $character->force = $data['force'];
                $character->money = $data['money'];
                $character->avatar = basename($data['avatar']); // On ne garde que le nom du fichier (le constructeur se chargera de mettre le chemin complet)

                $repository->update($character);
            } else {
                // Création
                $newCharacter = new Personnage(
                    $data['name'],
                    $data['PV'],
                    $data['PVMax'],
                    $data['force'],
                    6, // facesDe par défaut
                    50, // chance par défaut
                    $data['money'],
                    basename($data['avatar']) // On ne garde que le nom du fichier (pareil ci-dessus)
                );
                $repository->add($newCharacter);
            }

            header("Location: /personnage");
            exit();
        }

        // Affichage du formulaire
        $form = $this->moteur->render('personnageFormView', [
            'character' => $character
        ]);

        echo $this->moteur->render('indexView', [
            'contenu' => $form,
            'header' => $this->moteur->render('headerView'),
            'footer' => $this->moteur->render('footerView')
        ]);
    }
}
