<?php

require_once 'Tache.php'; // on importe la classe Tache

// Interface du stockage
interface TacheStorage {
    public function charger(): array;
    public function enregistrer(array $taches): void;
}

// Stockage dans un fichier texte
class TacheStorageFichier implements TacheStorage {
    private string $chemin;

    public function __construct(string $chemin) {
        $this->chemin = $chemin;
    }

    // Charger le fichier et transformer chaque ligne en objet Tache
    public function charger(): array {
        if (!file_exists($this->chemin)) {
            return [];
        }

        $lignes = file($this->chemin, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $taches = [];

        foreach ($lignes as $ligne) {
            $tache = Tache::fromLigne($ligne);
            if ($tache !== null) {
                $taches[] = $tache;
            }
        }

        return $taches;
    }

    // Convertir chaque Tache en ligne de texte puis enregistrer dans le fichier
    public function enregistrer(array $taches): void {
        $contenu = '';
        foreach ($taches as $tache) {
            $contenu .= $tache->toLigne() . PHP_EOL;
        }
        file_put_contents($this->chemin, $contenu);
    }
}



