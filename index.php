<?php
session_start();

require_once 'Tache.php';
require_once 'Taches.php';
require_once 'TacheStorageMySQL.php';

$storage = new TacheStorageMySQL();
$taches = $storage->charger();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texte = isset($_POST['tache']) && is_string($_POST['tache']) ? trim($_POST['tache']) : '';
    $priorite = isset($_POST['priorite']) ? trim($_POST['priorite']) : '';
    $terminee = false;

    $erreurs = [];

    if ($texte === '' || !preg_match('/[a-zA-Z0-9]/u', $texte)) {
        $erreurs[] = "Le champ de tâche est vide ou invalide.";
    } elseif (mb_strlen($texte) > 100) {
        $erreurs[] = "La tâche est trop longue (100 caractères max).";
    }

    $prioritesValides = ['normale', 'importante', 'urgente'];
    if (!in_array($priorite, $prioritesValides)) {
        $erreurs[] = "La priorité choisie est invalide.";
    }

    if (empty($erreurs)) {
        $nouvelleTache = new Tache($texte, $priorite, $terminee);
        $taches[] = $nouvelleTache;
        $storage->enregistrer($taches);
        $_SESSION['message'] = "✅ Tâche ajoutée avec succès.";
    } else {
        $_SESSION['message'] = "⚠️ " . implode(' ', $erreurs);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['supprimer'])) {
    $indexASupprimer = (int) $_GET['supprimer'];
    if (isset($taches[$indexASupprimer])) {
        unset($taches[$indexASupprimer]);
        $taches = array_values($taches);
        $storage->enregistrer($taches);
        $_SESSION['message'] = "🗑️ Tâche supprimée.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
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

    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="tache" placeholder="Ajouter une tâche" required>
        <select name="priorite" required>
            <option value="normale">🟢 Normale</option>
            <option value="importante">🟡 Importante</option>
            <option value="urgente">🔴 Urgente</option>
        </select>
        <button type="submit">Ajouter</button>
    </form>

    <ul class="taches">
    <?php foreach ($taches as $index => $tache) : ?>
        <li class="tache <?= 'priorite-' . htmlspecialchars($tache->getPriorite()) ?> <?= $tache->estTerminee() ? 'terminee' : '' ?>">
            <input 
                type="checkbox"
                class="checkbox-terminee"
                data-id="<?= $tache->getId() ?>"
                <?= $tache->estTerminee() ? 'checked' : '' ?>
            >
            <span class="texte">
                <?= htmlspecialchars($tache->getTexte()) ?>
            </span>
            <a href="?supprimer=<?= $index ?>">🗑️</a>
        </li>
    <?php endforeach; ?>
</ul>





    <script src="main.js"></script>
</body>
</html>




