<?php

// 🧱 Interface : définit ce que tout système de stockage (fichier, base de données...) doit faire
interface TacheStorage {
    public function charger(): array;                      // Méthode pour charger les tâches
    public function enregistrer(array $taches): void;      // Méthode pour enregistrer les tâches
}

// 📦 Classe concrète : gère le stockage des tâches dans un fichier texte
class TacheStorageFichier implements TacheStorage {
    private string $chemin;  // Chemin vers le fichier texte

    // 🔧 Constructeur : on lui fournit le chemin vers le fichier
    public function __construct(string $chemin) {
        $this->chemin = $chemin;
    }

    // 📥 Lire et transformer chaque ligne du fichier en tâche
    public function charger(): array {
        if (!file_exists($this->chemin)) {
            return []; // Aucun fichier = aucune tâche
        }

        $lignes = file($this->chemin, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $taches = [];

        foreach ($lignes as $ligne) {
            $parts = explode('|', $ligne); // Chaque ligne est sous la forme texte|priorite
            $texte = isset($parts[0]) ? trim($parts[0]) : '';
            $priorite = isset($parts[1]) ? trim($parts[1]) : 'normale'; // par défaut
            $taches[] = ['texte' => $texte, 'priorite' => $priorite];
        }

        return $taches; // On retourne toutes les tâches sous forme de tableau
    }

    // 📤 Convertir un tableau de tâches en texte et l’écrire dans le fichier
    public function enregistrer(array $taches): void {
        $contenu = '';

        foreach ($taches as $tache) {
            // Formater chaque ligne sous forme "texte|priorité"
            $ligne = trim($tache['texte']) . '|' . trim($tache['priorite']);
            $contenu .= $ligne . PHP_EOL;
        }

        // Écrire dans le fichier
        file_put_contents($this->chemin, $contenu);
    }
}


