<?php
/**
 * =====================================================
 * Suppression groupée des tâches marquées comme terminées
 * =====================================================
 * Ce script est appelé via AJAX depuis le bouton "Supprimer toutes les tâches terminées".
 * Il supprime en base toutes les tâches ayant le statut `terminee = 1`.
 */

// Connexion à la base de données
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Requête SQL : suppression des tâches terminées
    $sql = "DELETE FROM taches WHERE terminee = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Réponse JSON attendue par le script JS
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // En cas d’erreur SQL, on renvoie un message clair au client
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la suppression : ' . $e->getMessage()
    ]);
}




