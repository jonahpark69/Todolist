<?php
// 🟢 Démarrage de la session pour gérer les messages (succès, erreur)
session_start();

// 🔗 Inclusion du système de stockage des tâches
require_once 'taches.php';

// 📁 Instanciation du gestionnaire de tâches en mode fichier texte
$storage = new TacheStorageFichier("data/taches.txt");

// 📥 Chargement initial des tâches
$taches = $storage->charger();

//////////////////////////
// ✍️ TRAITEMENT DU FORMULAIRE D’AJOUT DE TÂCHE
//////////////////////////
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 🔒 Sécurisation et nettoyage des champs du formulaire
    $texte = isset($_POST['tache']) && is_string($_POST['tache']) ? trim($_POST['tache'], " \t\n\r\0\x0B\"'") : '';
    $priorite = isset($_POST['priorite']) ? trim($_POST['priorite']) : '';

    $erreurs = [];

    // ✅ Validation du texte : présence de caractères visibles
    if ($texte === '' || !preg_match('/[a-zA-Z0-9]/u', $texte)) {
        $erreurs[] = "Le champ de tâche est vide ou invalide.";
    } elseif (mb_strlen($texte) > 100) {
        $erreurs[] = "La tâche est trop longue (100 caractères max).";
    }

    // ✅ Validation de la priorité (valeurs autorisées)
    $prioritesValides = ['normale', 'importante', 'urgente'];
    if (!in_array($priorite, $prioritesValides)) {
        $erreurs[] = "La priorité choisie est invalide.";
    }

    // 🧠 Enregistrement si tout est valide
    if (empty($erreurs)) {
        $nouvelleTache = [
            'texte' => $texte,
            'priorite' => $priorite
        ];
        $taches[] = $nouvelleTache;
        $storage->enregistrer($taches);
        $_SESSION['message'] = "✅ Tâche ajoutée avec succès.";
    } else {
        $_SESSION['message'] = "⚠️ " . implode(' ', $erreurs);
    }

    // 🔁 Redirection pour éviter la double soumission du formulaire
    header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
    exit;
}

//////////////////////////
// ❌ TRAITEMENT DE LA SUPPRESSION DE TÂCHE
//////////////////////////
if (isset($_GET['supprimer'])) {
    $indexASupprimer = (int) $_GET['supprimer'];

    if (isset($taches[$indexASupprimer])) {
        unset($taches[$indexASupprimer]);
        $taches = array_values($taches); // Réindexation propre
        $storage->enregistrer($taches);
        $_SESSION['message'] = "🗑️ Tâche supprimée.";
    }

    // 🔁 Redirection après suppression
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

    <!-- 💬 Message de retour (succès ou erreur) -->
    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- 🆕 Formulaire d’ajout de tâche -->
    <form method="POST">
        <input type="text" name="tache" placeholder="Ajouter une tâche" required>
        <select name="priorite" required>
            <option value="normale">🟢 Normale</option>
            <option value="importante">🟡 Importante</option>
            <option value="urgente">🔴 Urgente</option>
        </select>
        <button type="submit">Ajouter</button>
    </form>

    <!-- 📋 Liste des tâches -->
    <ul>
        <?php if (empty($taches)): ?>
            <li>Aucune tâche pour le moment !</li>
        <?php else: ?>
            <?php foreach ($taches as $index => $tache): ?>
                <li class="<?= htmlspecialchars($tache['priorite']) ?>">
                    <span class="texte"><?= htmlspecialchars($tache['texte']) ?></span>
                    <span class="badge"><?= ucfirst($tache['priorite']) ?></span>
                    <a href="?supprimer=<?= $index ?>" onclick="return confirm('Supprimer cette tâche ?')">❌</a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <!-- 🔗 JavaScript optionnel -->
    <script src="main.js"></script>
</body>
</html>


