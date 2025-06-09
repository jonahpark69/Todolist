<?php
require_once 'TacheStorageMySQL.php';

$storage = new TacheStorageMySQL();
$storage->supprimerTachesTerminees();

// 🔧 Corrige ici : on envoie du JSON au lieu de texte
header('Content-Type: application/json');
echo json_encode(['success' => true]);


