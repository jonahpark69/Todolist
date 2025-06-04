<?php
session_start();

require_once 'Tache.php';
require_once 'Taches.php';
require_once 'config.php';
require_once 'TacheStorageMySQL.php';

// Connexion à la base via PDO
$storage = new TacheStorageMySQL();
$taches = $storage->charger();

//////////////////////////
// ✍️ TRAITEMENT FORMULAIRE AJOUT
//////////////////////////

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $texte = isset($_POST['tache']) ? trim($_POST['tache'], " \t\n\r\0\x0B\"'") : '';
    $priorite = isset($_POST['priorite']) ? trim($_POST['priorite']) : '';
    $erreurs = [];

    // Vérification du texte
    if ($texte === '' || !preg_match('/[a-zA-Z0-9]/u', $texte)) {
        $erreurs[] = "Le champ de tâche est vide ou invalide.";
    } elseif (mb_strlen($texte) > 100) {
        $erreurs[] = "La tâche est trop longue (100 caractères max).";
    }

    // Vérification de la priorité
    $prioritesValides = ['normale', 'importante', 'urgente'];
    if (!in_array($priorite, $prioritesValides)) {
        $erreurs[] = "La priorité choisie est invalide.";
    }

    // Enregistrement si valide
    if (empty($erreurs)) {
        $taches[] = new Tache($texte, $priorite);
        $storage->enregistrer($taches);
        $_SESSION['message'] = "✅ Tâche ajoutée avec succès.";

        header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
        exit;
    } else {
        $_SESSION['message'] = "⚠️ " . implode(' ', $erreurs);
    }
}

//////////////////////////
// ❌ SUPPRESSION DE TÂCHE
//////////////////////////
if (isset($_GET['supprimer'])) {
    $indexASupprimer = (int) $_GET['supprimer'];
    if (isset($taches[$indexASupprimer])) {
        unset($taches[$indexASupprimer]);
        $taches = array_values($taches);
        $storage->enregistrer($taches);
        $_SESSION['message'] = "🗑️ Tâche supprimée.";
    }

    header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>📝 Ma liste de tâches</h1>

    <!-- Message flash -->
    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Formulaire -->
    <form method="POST">
        <input type="text" name="tache" placeholder="Ajouter une tâche" required>
        <select name="priorite" required>
            <option value="normale">🟢 Normale</option>
            <option value="importante">🟡 Importante</option>
            <option value="urgente">🔴 Urgente</option>
        </select>
        <button type="submit">Ajouter</button>
    </form>

    <!-- Liste des tâches -->
    <ul>
        <?php if (empty($taches)): ?>
            <li>Aucune tâche pour le moment !</li>
        <?php else: ?>
            <?php foreach ($taches as $index => $tache): ?>
                <li class="<?= htmlspecialchars($tache->getPriorite()) ?>">
                    <span class="texte"><?= htmlspecialchars($tache->getTexte()) ?></span>
                    <span class="badge"><?= ucfirst($tache->getPriorite()) ?></span>
                    <a href="?supprimer=<?= $index ?>" onclick="return confirm('Supprimer cette tâche ?')">❌</a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <script src="script.js"></script>
</body>
</html>



