<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);

// Vérifier l'existence de l'étudiant
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT e.id, e.nom, e.prenom, e.filiere_id
     FROM etudiants e WHERE e.id = ?'
);
$stmt->execute([$id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    header('Location: index.php?error=notfound');
    exit;
}

// Traitement de la mise à jour (POST)
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = trim($_POST['nom']      ?? '');
    $prenom   = trim($_POST['prenom']   ?? '');
    $filiereId = (int) ($_POST['filiere_id'] ?? 0);

    // Validation serveur
    if ($nom === '')        $errors[] = 'Le nom est requis.';
    if ($prenom === '')     $errors[] = 'Le prénom est requis.';
    if ($filiereId <= 0)   $errors[] = 'La filière est requise.';

    if (empty($errors)) {
        try {
            $stmtUpdate = $pdo->prepare(
                'UPDATE etudiants SET nom = :nom, prenom = :prenom, filiere_id = :filiere_id
                 WHERE id = :id'
            );
            $stmtUpdate->execute([
                ':nom'        => $nom,
                ':prenom'     => $prenom,
                ':filiere_id' => $filiereId,
                ':id'         => $id
            ]);

            header('Location: index.php?success=updated');
            exit;

        } catch (PDOException $e) {
            $errors[] = 'Erreur lors de la mise à jour. Veuillez réessayer.';
        }
    }

    // En cas d'erreur, mettre à jour les valeurs affichées
    $etudiant['nom']       = htmlspecialchars($nom);
    $etudiant['prenom']    = htmlspecialchars($prenom);
    $etudiant['filiere_id'] = $filiereId;
}

// Récupérer les filières
$stmtFilieres = $pdo->query('SELECT id, nom FROM filieres ORDER BY nom');
$filieres = $stmtFilieres->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'étudiant — Gestion Étudiants</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav>
    <a href="index.php" class="brand">Gestion<span>Étudiants</span></a>
</nav>

<main>

    <div class="card">
        <h1 class="page-title">Modifier l'étudiant</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                ✕ <?= implode(' — ', array_map('htmlspecialchars', $errors)) ?>
            </div>
        <?php endif; ?>

        <form id="form-update" action="update.php?id=<?= $id ?>" method="POST" novalidate>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom"
                       value="<?= htmlspecialchars($etudiant['nom']) ?>"
                       placeholder="Ex : AHOUNOU" autocomplete="off">
                <span class="field-error" id="nom-error"></span>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom"
                       value="<?= htmlspecialchars($etudiant['prenom']) ?>"
                       placeholder="Ex : Vital" autocomplete="off">
                <span class="field-error" id="prenom-error"></span>
            </div>

            <div class="form-group">
                <label for="filiere_id">Filière</label>
                <select id="filiere_id" name="filiere_id">
                    <option value="0">— Choisir une filière —</option>
                    <?php foreach ($filieres as $f): ?>
                        <option value="<?= $f['id'] ?>"
                            <?= $f['id'] == $etudiant['filiere_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="filiere-error"></span>
            </div>

            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">✓ Enregistrer</button>
                <a href="index.php" class="btn btn-secondary">← Annuler</a>
            </div>
        </form>
    </div>

</main>

<script src="assets/js/script.js"></script>
</body>
</html>
// Update logic
