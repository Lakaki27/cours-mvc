<?php

namespace App;

class MoteurDeRendu
{
    public function render($template, $data = [])
    {
        extract($data); // Transforme chaque clé du tableau en variable
        // Par exemple : ['attaquant' => 'Vampire', 'cible' => 'Guerrier'] 
        // devient $attaquant = 'Vampire' et $cible = 'Guerrier'
        ob_start(); // Commence la mise en mémoire tampon de sortie
        require "../view/{$template}.php"; // Charge le fichier de vue
        return ob_get_clean(); // Récupère le contenu mis en tampon et le retourne
    }
}
