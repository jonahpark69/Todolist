<?php

/**
 * ========================================================
 * Interface TacheStorage
 * ========================================================
 * Définit les méthodes de base pour tout système de stockage de tâches.
 * Peut être implémentée avec une base de données, un fichier texte, etc.
 */
interface TacheStorage {
    public function charger(): array;                   // Récupère toutes les tâches
    public function enregistrer(array $taches): void;   // Enregistre toutes les tâches
}


/**
 * ========================================================
 * TacheStorageFichier — Implémentation fichier texte (.txt)
 * ========================================================
 * Ce système lit/écrit les tâches depuis un fichier texte structuré.
 * Obsolète si on utilise MySQL.
 */
class TacheStorageFichier implements TacheStorage {
    private string $chemin; // Chemin vers le fichier de stockage (.txt)

    public function __construct(string $chemin) {
        $this->chemin = $chemin;
    }

    // Lire et transformer chaque ligne du fichier en tableau de tâches
    public function charger(): array {
        if (!file_exists($this->chemin)) return [];

        $lignes = file($this->chemin, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $taches = [];

        foreach ($lignes as $ligne) {
            $parts = explode('|', $ligne);
            if (count($parts) === 3) {
                [$texte, $priorite, $terminee] = $parts;
                $taches[] = new Tache(null, $texte, $priorite, (int)$terminee);
            }
        }

        return $taches;
    }

    // Convertit et sauvegarde les tâches dans le fichier .txt
    public function enregistrer(array $taches): void {
        $contenu = "";

        foreach ($taches as $tache) {
            $contenu .= implode('|', [
                $tache->getTexte(),
                $tache->getPriorite(),
                $tache->estTerminee()
            ]) . PHP_EOL;
        }

        file_put_contents($this->chemin, $contenu);
    }
}



