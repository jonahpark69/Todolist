<?php
require_once 'TacheStorageMySQL.php';

$id = $_POST['id'] ?? null;
$texte = $_POST['texte'] ?? null;

if ($id !== null && !empty($texte)) {
    $storage = new TacheStorageMySQL();
    $storage->modifierTexte($id, $texte);
    http_response_code(200);
    echo "OK";
} else {
    http_response_code(400);
    echo "Paramètres invalides";
}
