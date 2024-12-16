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
    public function edit(?int $id = null)
    {
        // Vérifie si on est en mode édition
        $character = null;
        if ($id) {
            // Si un ID est fourni, on tente de récupérer le personnage correspondant
            $character = $this->repository->getById($id);
            if (!$character) {
                // Si le personnage n'existe pas, on redirige vers la page d'accueil (on pourrait aussi afficher un message d'erreur)
                header("Location: /");
                exit();
            }
        }

        // Si la méthode est post, on traite le formulaire qui a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mise à jour
            $data = [
                'name' => $_POST['name'],
                'avatar' => $_POST['avatar']
            ];
            $character->setNom($data['name']);
            $character->setAvatar(basename($data['avatar'])); // On ne garde que le nom du fichier (le constructeur se chargera de mettre le chemin complet)

            $this->repository->update($character);
            // Création

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

    public function create()
    {
        $character = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'PVMax' => intval($_POST["PVMax"]),
                'force' => intval($_POST["force"]),
                'avatar' => $_POST['avatar']
            ];
            
            $character = new Personnage(
                $data['name'],
                $data['PVMax'], //PVs par défaut = PVMax
                $data['PVMax'],
                $data['force'],
                6, // facesDe par défaut
                50, // chance par défaut
                0, //money par défaut
                basename($data['avatar']) // On ne garde que le nom du fichier (pareil ci-dessus)
            );

            $this->repository->add($character);

            header("Location: /personnage");
            exit();
        }

        $form = $this->moteur->render('newPersonnageFormView', []);

        echo $this->moteur->render('indexView', [
            'contenu' => $form,
            'header' => $this->moteur->render('headerView'),
            'footer' => $this->moteur->render('footerView')
        ]);
    }
}
