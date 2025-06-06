<?php

require_once 'config.php';   // ⚙️ Connexion PDO via fichier séparé
require_once 'Tache.php';    // 📦 Modèle de données

class TacheStorageMySQL {
    private PDO $pdo;

    public function __construct() {
        // 💾 Connexion à MySQL (via config.php)
        $this->pdo = getPDO();
    }

    /**
     * 🔽 Charge toutes les tâches depuis la BDD
     * @return Tache[]
     */
    public function charger(): array {
        $taches = [];

        $stmt = $this->pdo->query("SELECT texte, priorite, terminee FROM taches");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $texte = $row['texte'];
            $priorite = $row['priorite'];
            $terminee = (bool) $row['terminee']; // 🔄 convertit 1/0 en true/false

            $taches[] = new Tache($texte, $priorite, $terminee);
        }

        return $taches;
    }

    /**
     * 💾 Enregistre toutes les tâches dans la base
     * ⚠️ Efface tout et réinsère tout à chaque appel
     * @param Tache[] $taches
     */
    public function enregistrer(array $taches): void {
        // 🧹 Supprime toutes les tâches existantes
        $this->pdo->exec("DELETE FROM taches");

        // 🔁 Prépare l’insertion
        $stmt = $this->pdo->prepare("
            INSERT INTO taches (texte, priorite, terminee)
            VALUES (:texte, :priorite, :terminee)
        ");

        // 💡 Enregistre chaque tâche
        foreach ($taches as $tache) {
            $stmt->execute([
                ':texte' => $tache->getTexte(),
                ':priorite' => $tache->getPriorite(),
                ':terminee' => $tache->estTerminee() ? 1 : 0
            ]);
        }
    }
}
