<?php
/**
 * ==========================================================================
 * update-terminee.php
 * ==========================================================================
 * Met à jour le statut "terminée" d'une tâche (via AJAX)
 * Reçoit : id (int) et terminee (0 ou 1) via POST
 * Retourne : JSON { success: true } ou erreur
 */

require_once 'TacheStorageMySQL.php';

header('Content-Type: application/json');

// Récupération des données envoyées en POST
$id       = $_POST['id']       ?? null;
$terminee = $_POST['terminee'] ?? null;

// Vérification des paramètres requis
if ($id !== null && $terminee !== null) {
    try {
        $storage = new TacheStorageMySQL();
        $storage->majStatut((int)$id, (int)$terminee);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // En cas d’erreur serveur ou SQL
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Erreur serveur']);
    }
} else {
    // Requête incomplète
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
}


