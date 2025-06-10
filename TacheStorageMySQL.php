<?php

require_once 'Tache.php';

class TacheStorageMySQL {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=todolist_db;charset=utf8',
            'root',
            'root'
        );
    }

    /* ─────────────── RÉCUPÉRER TOUTES LES TÂCHES ─────────────── */
    public function lireToutes() {
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

    /* ─────────────── CRÉER UNE TÂCHE ─────────────── */
    public function creer(Tache $tache) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO taches (texte, priorite, terminee)
             VALUES (:texte, :priorite, :terminee)"
        );
        $stmt->execute([
            ':texte'     => $tache->getTexte(),
            ':priorite'  => $tache->getPriorite(),
            ':terminee'  => $tache->estTerminee()
        ]);
        $tache->setId($this->pdo->lastInsertId());
        return $tache;
    }

    /* ─────────────── SUPPRIMER UNE TÂCHE ─────────────── */
    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM taches WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /* ─────────────── METTRE À JOUR « TERMINÉE » ─────────────── */
    public function marquerCommeTerminee($id, $terminee) {
        $stmt = $this->pdo->prepare(
            "UPDATE taches SET terminee = :terminee WHERE id = :id"
        );
        $stmt->execute([
            ':terminee' => $terminee,
            ':id'       => $id
        ]);
    }

    /* ─────────────── MODIFIER LE TEXTE ─────────────── */
    public function modifierTexte($id, $texte) {
        $stmt = $this->pdo->prepare(
            "UPDATE taches SET texte = :texte WHERE id = :id"
        );
        $stmt->execute([
            ':texte' => $texte,
            ':id'    => $id
        ]);
    }

    /* ─────────────── SUPPRIMER TOUTES LES TERMINÉES (historique) ─────────────── */
    public function supprimerTachesTerminees() {
        $this->pdo->exec("DELETE FROM taches WHERE terminee = 1");
    }

    /* ─────────────── NOUVELLE MÉTHODE JOUR 6 ÉTAPE 3 ───────────────
       • Alias lisible pour l’endpoint delete-all-completed.php
       • Appelle simplement la méthode historique ci-dessus.                */
    public function supprimerToutesTerminees() {
        $this->supprimerTachesTerminees();
    }
}





