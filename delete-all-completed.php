<?php
/**
 * delete-all-completed.php
 * Supprime en base toutes les tâches marquées comme terminées
 * et renvoie un JSON {success:true|false}.
 */

require_once 'TacheStorageMySQL.php';

header('Content-Type: application/json');

try {
    $storage = new TacheStorageMySQL();
    // ► méthode à ajouter dans TacheStorageMySQL.php (voir plus bas)
    $storage->supprimerToutesTerminees();

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}



