<?php

/**
 * ========================================================
 * TacheStorageMySQL
 * ========================================================
 * Classe de stockage en base de données MySQL via PDO.
 * Permet de créer, lire, mettre à jour et supprimer des tâches.
 */

require_once 'Tache.php';

class TacheStorageMySQL {
    private $pdo;

    /**
     * Connexion à la base de données MySQL
     */
    public function __construct() {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=todolist_db;charset=utf8',
            'root',
            'root'
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /* ─────────────────────────────────────────────────────
     * Lire toutes les tâches (retourne un tableau de Tache)
     * ───────────────────────────────────────────────────── */
    public function lireToutes(): array {
        $taches = [];
        $query  = $this->pdo->query("SELECT * FROM taches");

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $taches[] = new Tache(
                $row['id'],
                $row['texte'],
                $row['priorite'],
                $row['terminee']
            );
        }

        return $taches;
    }

    /* ─────────────────────────────────────────────────────
     * Créer une nouvelle tâche en base de données
     * ───────────────────────────────────────────────────── */
    public function creer(Tache $tache): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO taches (texte, priorite, terminee)
            VALUES (:texte, :priorite, :terminee)
        ");

        $stmt->execute([
            'texte'     => $tache->getTexte(),
            'priorite'  => $tache->getPriorite(),
            'terminee'  => $tache->estTerminee(),
        ]);

        $tache->setId($this->pdo->lastInsertId());
    }

    /* ─────────────────────────────────────────────────────
     * Supprimer une tâche par son ID
     * ───────────────────────────────────────────────────── */
    public function supprimer(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM taches WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    /* ─────────────────────────────────────────────────────
     * Supprimer toutes les tâches terminées
     * ───────────────────────────────────────────────────── */
    public function supprimerToutesTerminees(): void {
        $this->pdo->exec("DELETE FROM taches WHERE terminee = 1");
    }

    /* ─────────────────────────────────────────────────────
     * Mettre à jour le texte d'une tâche
     * ───────────────────────────────────────────────────── */
    public function majTexte(int $id, string $texte): void {
        $stmt = $this->pdo->prepare("
            UPDATE taches SET texte = :texte WHERE id = :id
        ");
        $stmt->execute([
            'id'    => $id,
            'texte' => $texte
        ]);
    }

    /* ─────────────────────────────────────────────────────
     * Mettre à jour le statut "terminée" d'une tâche
     * ───────────────────────────────────────────────────── */
    public function majStatut(int $id, int $terminee): void {
        $stmt = $this->pdo->prepare("
            UPDATE taches SET terminee = :terminee WHERE id = :id
        ");
        $stmt->execute([
            'id'        => $id,
            'terminee'  => $terminee
        ]);
    }
}






