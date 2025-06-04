<?php
require_once 'Tache.php';

require_once 'config.php';

class TacheStorageMySQL implements TacheStorage {
    private PDO $pdo;

    public function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function charger(): array {
        $stmt = $this->pdo->query("SELECT * FROM taches ORDER BY id DESC");
        $taches = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $taches[] = new Tache($row['texte'], $row['priorite']);
        }
        return $taches;
    }

    public function enregistrer(array $taches): void {
        $this->pdo->exec("DELETE FROM taches");

        $stmt = $this->pdo->prepare("INSERT INTO taches (texte, priorite) VALUES (:texte, :priorite)");
        foreach ($taches as $tache) {
            $stmt->execute([
                ':texte' => $tache->getTexte(),
                ':priorite' => $tache->getPriorite()
            ]);
        }
    }
}
