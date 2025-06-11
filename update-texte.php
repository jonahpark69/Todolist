<?php
/**
 * ==========================================================================
 * update-texte.php
 * ==========================================================================
 * Met à jour le texte d'une tâche (via AJAX)
 * Reçoit : id (int) et texte (string) via POST
 * Retourne : "OK" ou message d’erreur
 */

require_once 'TacheStorageMySQL.php';

// Récupération des données envoyées
$id    = $_POST['id']    ?? null;
$texte = $_POST['texte'] ?? null;

// Vérification des paramètres
if ($id !== null && !empty(trim($texte))) {
    try {
        $storage = new TacheStorageMySQL();
        $storage->majTexte((int)$id, trim($texte));

        http_response_code(200);
        echo "OK";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Erreur serveur";
    }
} else {
    // Données manquantes ou invalides
    http_response_code(400);
    echo "Paramètres invalides";
}

