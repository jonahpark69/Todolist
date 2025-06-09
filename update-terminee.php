<?php
require_once 'TacheStorageMySQL.php';

$id = $_POST['id'] ?? null;
$terminee = $_POST['terminee'] ?? null;

header('Content-Type: application/json');

if ($id !== null && $terminee !== null) {
    $storage = new TacheStorageMySQL();
    $storage->marquerCommeTerminee($id, $terminee);
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
}

