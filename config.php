<?php
// Fichier de connexion PDO à la base de données
// À inclure dans toutes les pages PHP

define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_etudiants');
define('DB_USER', 'root');       // Modifier selon votre config
define('DB_PASS', '');           // Modifier selon votre config
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // En production, ne jamais afficher le message d'erreur brut
    die('<p class="error">Erreur de connexion à la base de données. Veuillez contacter l\'administrateur.</p>');
}
// Config PDO
