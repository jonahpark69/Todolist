<?php
require_once 'TacheStorageMySQL.php';
require_once 'Tache.php';

$storage = new TacheStorageMySQL();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter']) && !empty($_POST['texte'])) {
    $texte = $_POST['texte'];
    $priorite = $_POST['priorite'] ?? 'normale';
    $terminee = 0;
    $tache = new Tache(null, $texte, $priorite, $terminee);
    $storage->creer($tache);
    header("Location: index.php?message=ajout");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $storage->supprimer($id);
    header("Location: index.php?message=suppression");
    exit();
}

if (isset($_GET['message'])) {
    $message = match($_GET['message']) {
        'ajout' => "Tâche ajoutée avec succès.",
        'suppression' => "Tâche supprimée avec succès.",
        default => ""
    };
}

$taches = $storage->lireToutes();
?>

<!DOCTYPE html>
<html lang="fr" class="">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Todo List</title>

  <script>
    tailwind.config = {
      darkMode: 'class'
    };
  </script>
  <script>
  tailwind.config = {
    darkMode: 'class'
  };
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    darkMode: 'class'
  };
</script>
  <script src="main.js" defer></script>
</head>

<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition min-h-screen" class="min-h-screen bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition" class="transition min-h-screen bg-white text-gray-900 dark:bg-gray-900 dark:text-white">
  <div class="flex justify-end p-4">
    <button id="toggle-theme" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white transition">
      🌞 / 🌙
    </button>
  </div>

  <main class="px-4">
    <h1 class="text-3xl font-bold mb-6">Ma Todo List</h1>

    <?php if (!empty($message)) : ?>
      <div class="message mb-4 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded shadow dark:bg-green-800 dark:text-green-100">
        <?= htmlspecialchars($message) ?>
      </div>
      <script>
        if (window.history.replaceState) {
          window.history.replaceState(null, null, window.location.pathname);
        }
        setTimeout(() => {
          const msg = document.querySelector('.message');
          if (msg) msg.remove();
        }, 2000);
      </script>
    <?php endif; ?>

    <form action="" method="post" class="mb-6 flex flex-col sm:flex-row gap-2">
      <input type="text" name="texte" placeholder="Ajouter une tâche..." class="w-full p-2 border border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white" required />
      <select name="priorite" class="w-full sm:w-auto p-2 border border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        <option value="normale">Normale</option>
        <option value="importante">Importante</option>
        <option value="urgente">Urgente</option>
      </select>
      <button type="submit" name="ajouter" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ajouter</button>
    </form>

    <input
  type="text"
  id="recherche"
  placeholder="Rechercher une tâche..."
  class="w-full p-2 mb-4 border border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white"
  spellcheck="false"
  autocomplete="off"
/>



    <div class="tri mb-4 flex flex-col sm:flex-row gap-2">
      <button data-tri="texte" class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm hover:bg-gray-300 dark:bg-gray-700 dark:border-gray-500 dark:text-white">Trier A → Z</button>
      <button data-tri="priorite" class="px-3 py-1 bg-gray-200 border border-gray-300 rounded text-sm hover:bg-gray-300 dark:bg-gray-700 dark:border-gray-500 dark:text-white">Trier par priorité</button>
    </div>

    <div class="tache <?php if ($tache['terminee']) echo 'terminee'; ?>">


    <ul class="taches space-y-3">
      <?php foreach ($taches as $tache): ?>
        <?php
          $prio = $tache->getPriorite();
          $classePriorite = match($prio) {
              'urgente' => 'border-red-500',
              'importante' => 'border-yellow-400',
              default => 'border-green-500'
          };
          $textePriorite = match($prio) {
              'urgente' => '🔥 Urgente',
              'importante' => '⚠️ Importante',
              default => '✅ Normale'
          };
          $couleurPriorite = match($prio) {
              'urgente' => 'text-red-600',
              'importante' => 'text-yellow-500',
              default => 'text-green-600'
          };
        ?>
        <li class="tache flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded shadow border-l-4 <?= $classePriorite ?> <?= $tache->estTerminee() ? 'opacity-60 line-through terminee' : '' ?>" data-priorite="<?= $prio ?>">


          <div class="flex items-center gap-4 w-full">
            <span class="text-xs font-semibold <?= $couleurPriorite ?> min-w-[90px]"><?= $textePriorite ?></span>
            <input type="checkbox" class="checkbox-terminee" data-id="<?= $tache->getId(); ?>" ... >

            <span class="texte flex-grow break-words text-gray-900 dark:text-white">
  <?= htmlspecialchars(trim(preg_replace('/\s+/', ' ', $tache->getTexte()))) ?>
</span>


          </div>
          <a href="?supprimer=<?= $tache->getId() ?>" title="Supprimer cette tâche" class="text-red-500 hover:text-red-700 p-2 rounded transition-colors duration-200" aria-label="Supprimer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
            </svg>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="fixed bottom-6 inset-x-0 flex justify-center z-40">
  <button id="delete-all-completed" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
    Supprimer toutes les tâches terminées
  </button>
</div>


  </main>

  <div id="modal-confirm" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-sm w-full text-center">
      <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Supprimer la tâche ?</h2>
      <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Cette action est irréversible.</p>
      <div class="flex justify-center gap-4">
        <button id="cancel-btn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-white rounded">Annuler</button>
        <a id="confirm-btn" href="#" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Supprimer</a>
      </div>
    </div>
  </div>

<div id="modal-delete-all" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-xl text-center">
    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Supprimer toutes les tâches terminées ?</h2>
    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Cette action est irréversible.</p>
    <div class="flex justify-center gap-4">
      <button id="cancel-delete-all" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-500 transition">
        Annuler
      </button>
      <button id="confirm-delete-all" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
        Supprimer
      </button>
    </div>
  </div>
</div>


</body>
</html>










