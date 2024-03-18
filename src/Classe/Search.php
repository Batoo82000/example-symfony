<?php

namespace App\Classe;

class Search // Objet permettant de représenter la recherche effectuée par l'utilisateur
{
    /**
     * @var string
     */
    public $string = ''; // Stocke la recherche textuelle

    /**
     * @var Category[]
     */
    public $categories = []; // Stocke la recherche par selection dans l'entité category
}