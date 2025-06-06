<?php
require_once 'Tache.php';
require_once 'config.php';
require_once 'Taches.php';

class TacheStorageMySQL implements TacheStorage {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    // 🔁 Charger toutes les tâches depuis MySQL (avec l'ID !)
    public function charger(): array {
        $taches = [];
        $sql = "SELECT id, texte, priorite, terminee FROM taches"; // ✅ ajout de l'id
        $result = $this->pdo->query($sql);

        if ($result !== false) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $texte = isset($row['texte']) ? (string)$row['texte'] : '';
                $priorite = isset($row['priorite']) ? (string)$row['priorite'] : 'normale';
                $terminee = isset($row['terminee']) ? (bool)$row['terminee'] : false;
                $id = isset($row['id']) ? (int)$row['id'] : 0; // ✅ récupération de l'id

                $tache = new Tache($texte, $priorite, $terminee);
                $tache->setId($id); // ✅ on assigne l'id à l'objet
                $taches[] = $tache;
            }
        }

        return $taches;
    }

    // 💾 Enregistrer toutes les tâches en supprimant d’abord l'existant
    public function enregistrer(array $taches): void {
        // On efface toutes les lignes
        $this->pdo->exec("DELETE FROM taches");

        // Requête préparée pour ajouter les tâches
        $stmt = $this->pdo->prepare("INSERT INTO taches (texte, priorite, terminee) VALUES (:texte, :priorite, :terminee)");

        foreach ($taches as $tache) {
            if ($tache instanceof Tache) {
                $stmt->execute([
                    'texte' => $tache->getTexte(),
                    'priorite' => $tache->getPriorite(),
                    'terminee' => $tache->estTerminee() ? 1 : 0
                ]);
            }
        }
    }
}



