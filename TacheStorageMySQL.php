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

    public function lireToutes() {
        $taches = [];
        $query = $this->pdo->query("SELECT * FROM taches");
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

    public function creer(Tache $tache) {
        $stmt = $this->pdo->prepare("INSERT INTO taches (texte, priorite, terminee) VALUES (:texte, :priorite, :terminee)");
        $stmt->execute([
            ':texte' => $tache->getTexte(),
            ':priorite' => $tache->getPriorite(),
            ':terminee' => $tache->estTerminee()
        ]);
        $tache->setId($this->pdo->lastInsertId());
        return $tache;
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM taches WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    // ✅ Mise à jour du statut terminée
    public function marquerCommeTerminee($id, $terminee) {
        $stmt = $this->pdo->prepare("UPDATE taches SET terminee = :terminee WHERE id = :id");
        $stmt->execute([
            ':terminee' => $terminee,
            ':id' => $id
        ]);
    }

    // ✅ Modifier le texte d'une tâche
    public function modifierTexte($id, $texte) {
        $stmt = $this->pdo->prepare("UPDATE taches SET texte = :texte WHERE id = :id");
        $stmt->execute([
            ':texte' => $texte,
            ':id' => $id
        ]);
    }

    // ✅ Supprimer toutes les tâches terminées
    public function supprimerTachesTerminees() {
        $query = "DELETE FROM taches WHERE terminee = 1";
        $this->pdo->exec($query);
    }
}




