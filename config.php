<?php
function getPDO(): PDO {
    return new PDO(
        'mysql:host=localhost;dbname=todolist;charset=utf8mb4',
        'root',        // 🧑 Identifiant par défaut de MAMP
        'root',        // 🔒 Mot de passe par défaut de MAMP
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // 🔍 Active les erreurs PDO
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // 📦 Résultats en tableaux associatifs
        ]
    );
}
