<?php

namespace App;

require '../vendor/autoload.php';


use App\Controller\BagarreController;
use App\Controller\HallController;
use App\Controller\PersonnageController;

// L'index.php nous servira de routeur : c'est le point d'entrée de notre application :
// Il va traiter les requêtes HTTP et appeler les bons contrôleurs en fonction de l'URL demandée.

// Première étape : on récupère l'URL à partir de la requête HTTP :
// Le .htaccess redirige toutes les requêtes vers index.php, et ajoute l'URL demandée en paramètre GET 'page'.

// Vérifie si l'URL est présente dans les paramètres GET; sinon, utilise '/' comme valeur par défaut.
$url = $_GET['page'] ?? '/';

// Supprime les barres obliques en début et en fin de l'URL pour normaliser le chemin.
$url = trim($url, '/');

// var_dump($url);

// Divise l'URL en segments basés sur chaque barre oblique, stockant les résultats dans un tableau.
$segments = explode('/', $url);

// Affiche le tableau de segments pour déboguer (à supprimer ou commenter en prod, biensûr).
// var_dump($segments);

// Par exemple : 
// Si l'URL est '/bagarre/42', $segments contiendra ['bagarre', '42'].
// Si l'URL est '/about', $segments contiendra ['about'].
// Si l'URL est '/', $segments contiendra [].
// etc...

// Le fait d'adopter une structure de type /element1/element2/element3 permet de créer des routes plus facilement.
// Par exemple, on pourrait imaginer que /bagarre/42 affiche la bagarre n°42, et /about affiche la page "À propos".
// Que /bagarre/42/editer affiche un formulaire d'édition pour la bagarre n°42, etc...

// On appelle cette approche RESTful routing : https://en.wikipedia.org/wiki/Representational_state_transfer#Applied_to_Web_services
// C'est une convention très répandue pour structurer les applications web, notamment les API.

// On peut maintenant utiliser les segments pour déterminer quelle page afficher.

// Vérifie si le tableau de segments n'est pas vide pour éviter d'exécuter le switch sur un tableau vide.
if (!empty($segments)) {
    // Utilise le premier segment de l'URL comme indicateur pour le routage.
    switch ($segments[0]) {
            // Pour le CRUD personnage
        case 'personnage':
            // Vérifie si un second segment (ID) est présent.
            $personnageController = new PersonnageController();
            if (isset($segments[1])) {
                if (isset($segments[2])) {
                    if ($segments[2] === "delete") $personnageController->delete($segments[1]);
                } else {
                    if ($segments[1] === "ajouter") {
                        $personnageController->create(intval($segments[1]));
                    } else {
                        $personnageController->edit(intval($segments[1]));
                    }
                }
            } else {
                $personnageController->index();
            }
            break;
            // Cas où le premier segment de l'URL est 'bagarre'.
        case "hall":
            $hallController = new HallController();

            $hallController->afficherHall();
            break;
        case 'bagarre':
            $bagarreController = new BagarreController();
            // Vérifie si un second segment (ID) est présent.
            if (isset($segments[1]) && $segments[1] === "move") {
                // Affiche l'ID de la bagarre, en s'assurant que les caractères spéciaux sont convertis pour éviter les attaques XSS.
                echo json_encode($bagarreController->getNextTurn());
                return;
            } else {
                // S'il n'y a pas de second segment, affiche simplement une liste des bagarres.
                // Pour l'instant, on va lancer une bagarre entre Bob l'éponge et Dracula en appelant BagarreController :
                $bagarreController->afficherBagarre();
            }
            break;
            // Cas où le premier segment de l'URL est 'about'.
        case 'about':
            // Affiche la page "À propos".
            echo "Page À propos";
            break;
            // Si aucun des cas ci-dessus ne correspond, le comportement par défaut est d'afficher la page d'accueil.
        case '': // Cas où l'URL est vide : page d'accueil. (Pour l'instant on redirige vers la liste des personnages)
            header('Location: /personnage');
            exit();
            break;
        default:
            http_response_code(404); // Retourne un code HTTP 404 Not Found.
            echo "Erreur 404 : Page non trouvée.";
            break;
    }
}

http_response_code(500); // Retourne un code HTTP 500 Internal Server Error si le code atteint ce point.
