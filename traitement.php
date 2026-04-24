<?php
require_once 'config.php';

// Vérifier que la requête est bien POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Récupérer et nettoyer les données
$nom      = trim($_POST['nom']      ?? '');
$prenom   = trim($_POST['prenom']   ?? '');
$filiereId = (int) ($_POST['filiere_id'] ?? 0);

// Validation côté serveur (sécurité en plus du JS)
if ($nom === '' || $prenom === '' || $filiereId <= 0) {
    header('Location: index.php?error=validation');
    exit;
}

// Vérifier que la filière existe
$stmtCheck = $pdo->prepare('SELECT id FROM filieres WHERE id = ?');
$stmtCheck->execute([$filiereId]);
if (!$stmtCheck->fetch()) {
    header('Location: index.php?error=filiere');
    exit;
}

// Insertion sécurisée avec requête préparée
try {
    $stmt = $pdo->prepare(
        'INSERT INTO etudiants (nom, prenom, filiere_id) VALUES (:nom, :prenom, :filiere_id)'
    );
    $stmt->execute([
        ':nom'       => $nom,
        ':prenom'    => $prenom,
        ':filiere_id' => $filiereId
    ]);

    header('Location: index.php?success=added');
    exit;

} catch (PDOException $e) {
    header('Location: index.php?error=db');
    exit;
}
// Traitement PHP
