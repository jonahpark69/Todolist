<?php
/**
 * =======================================
 * Configuration de la base de données
 * =======================================
 * Ce fichier contient les informations de connexion à la base MySQL.
 * Il est inclus dans tous les fichiers nécessitant un accès à la BDD.
 */

// Informations de connexion à adapter à votre environnement local
$host     = 'localhost';
$dbname   = 'todolist';
$username = 'root';
$password = 'root';

// Connexion PDO avec gestion des erreurs
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Affichage d’un message d’erreur clair en cas d’échec
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

