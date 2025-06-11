<?php
/**
 * ======================================================
 * Page principale de l’application To-Do List (index.php)
 * ======================================================
 * - Gère l’ajout et la suppression des tâches via formulaire ou requête GET
 * - Affiche la liste des tâches à l’utilisateur
 * - Utilise TacheStorageMySQL pour communiquer avec la base
 */

require_once 'TacheStorageMySQL.php';
require_once 'Tache.php';

$storage = new TacheStorageMySQL();
$message = "";

// ----------------------------------------------------
// AJOUT d'une tâche (via POST)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter']) && !empty($_POST['texte'])) {
    $texte     = $_POST['texte'];
    $priorite  = $_POST['priorite'] ?? 'normale';
    $terminee  = 0;

    $tache = new Tache(null, $texte, $priorite, $terminee);
    $storage->creer($tache);

    header("Location: index.php?message=ajout");
    exit();
}

// ----------------------------------------------------
// SUPPRESSION d'une tâche (via GET)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $storage->supprimer($id);

    header("Location: index.php?message=suppression");
    exit();
}

// ----------------------------------------------------
// Message de confirmation
// ----------------------------------------------------
if (isset($_GET['message'])) {
    $message = match($_GET['message']) {
        'ajout'       => "Tâche ajoutée avec succès.",
        'suppression' => "Tâche supprimée avec succès.",
        default       => ""
    };
}

// ----------------------------------------------------
// Récupération de toutes les tâches à afficher
// ----------------------------------------------------
$taches = $storage->lireToutes();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ma To-Do List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="main.js"></script>
  <script defer src="success.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white">

  <main class="max-w-3xl mx-auto p-4">

    <!-- Titre -->
    <h1 class="text-3xl font-bold mb-6 text-center">Ma To-Do List</h1>

    <!-- Message de feedback -->
    <?php if ($message): ?>
      <div class="message bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <!-- Formulaire d’ajout -->
    <form method="POST" class="flex flex-col sm:flex-row gap-2 mb-6">
      <input type="text" name="texte" placeholder="Ajouter une tâche..." required
             class="flex-grow p-2 border rounded dark:bg-gray-800 dark:text-white" />

      <select name="priorite" class="p-2 border rounded dark:bg-gray-800 dark:text-white">
        <option value="normale">Normale</option>
        <option value="importante">Importante</option>
        <option value="urgente">Urgente</option>
      </select>

      <button type="submit" name="ajouter"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Ajouter
      </button>
    </form>

    <!-- Barre de recherche -->
    <input type="text" id="recherche" placeholder="Rechercher une tâche..."
           class="champ-recherche dark:bg-gray-800 dark:text-white" />

    <!-- Boutons de tri et vue -->
    <div class="tri flex justify-between items-center mb-4">
      <div>
        <button data-tri="texte">A → Z</button>
        <button data-tri="priorite">Par priorité</button>
      </div>
      <button id="toggleVue">Vue Grille</button>
    </div>

    <!-- Liste des tâches -->
    <ul id="liste-taches" class="taches flex flex-col gap-2">
      <?php foreach ($taches as $tache): ?>
        <li class="tache <?= $tache->estTerminee() ? 'terminee opacity-60' : '' ?> priorite-<?= $tache->getPriorite() ?>"
            data-priorite="<?= $tache->getPriorite() ?>">

          <input type="checkbox"
                 class="checkbox-terminee mr-2"
                 data-id="<?= $tache->getId() ?>"
                 <?= $tache->estTerminee() ? 'checked' : '' ?> />

          <span class="texte"><?= htmlspecialchars($tache->getTexte()) ?></span>

          <a href="?supprimer=<?= $tache->getId() ?>"
             class="text-red-600 hover:underline ml-4">🗑️</a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Suppression groupée -->
    <button id="delete-all-completed"
            class="mt-6 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
      Supprimer toutes les tâches terminées
    </button>

  </main>

  <!-- Modale suppression individuelle -->
  <div id="modal-confirm" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg">
      <p class="mb-4">Voulez-vous vraiment supprimer cette tâche ?</p>
      <div class="flex justify-end gap-4">
        <a id="confirm-btn" href="#" class="bg-red-600 text-white px-4 py-2 rounded">Oui</a>
        <button id="cancel-btn" class="bg-gray-300 px-4 py-2 rounded">Annuler</button>
      </div>
    </div>
  </div>

  <!-- Modale suppression groupée -->
  <div id="modal-delete-all" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg">
      <p class="mb-4">Supprimer toutes les tâches terminées ?</p>
      <div class="flex justify-end gap-4">
        <button id="confirm-delete-all" class="bg-red-600 text-white px-4 py-2 rounded">Oui</button>
        <button id="cancel-delete-all" class="bg-gray-300 px-4 py-2 rounded">Annuler</button>
      </div>
    </div>
  </div>

  <!-- Animation succès (✔️ + confettis) -->
  <div id="successOverlay" class="hidden fixed inset-0 bg-black bg-opacity-30 z-50 items-center justify-center">
    <div id="successTick" class="text-green-500 text-7xl opacity-0 transition-transform scale-0">✔️</div>
    <canvas id="confettiCanvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
  </div>

</body>
</html>












