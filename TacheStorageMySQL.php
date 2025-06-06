<?php
require_once 'Tache.php';
require_once 'config.php';
require_once 'Taches.php';

class TacheStorageMySQL implements TacheStorage {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    // 🔁 Charger toutes les tâches depuis MySQL
    public function charger(): array {
        $taches = [];
        $sql = "SELECT texte, priorite, terminee FROM taches";
        $result = $this->pdo->query($sql);

        if ($result !== false) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $texte = isset($row['texte']) ? (string)$row['texte'] : '';
                $priorite = isset($row['priorite']) ? (string)$row['priorite'] : 'normale';
                $terminee = isset($row['terminee']) ? (bool)$row['terminee'] : false;

                $taches[] = new Tache($texte, $priorite, $terminee);
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


