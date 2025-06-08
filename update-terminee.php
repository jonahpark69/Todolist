<?php
require_once 'TacheStorageMySQL.php';

$id = $_POST['id'] ?? null;
$terminee = $_POST['terminee'] ?? null;

if ($id !== null && $terminee !== null) {
    $storage = new TacheStorageMySQL();
    $storage->marquerCommeTerminee($id, $terminee);
    http_response_code(200);
    echo "OK";
} else {
    http_response_code(400);
    echo "Paramètres manquants";
}