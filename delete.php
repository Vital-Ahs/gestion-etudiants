<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Vérifier l'existence de l'étudiant avant suppression
$stmt = $pdo->prepare('SELECT id, nom, prenom FROM etudiants WHERE id = ?');
$stmt->execute([$id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    header('Location: index.php?error=notfound');
    exit;
}

// Suppression sécurisée avec requête préparée
try {
    $stmtDelete = $pdo->prepare('DELETE FROM etudiants WHERE id = ?');
    $stmtDelete->execute([$id]);

    header('Location: index.php?success=deleted');
    exit;

} catch (PDOException $e) {
    header('Location: index.php?error=db');
    exit;
}
