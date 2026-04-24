<?php
require_once 'config.php';

// Message de retour (après ajout/modif/suppression)
$message = '';
$messageType = '';
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':
            $message = 'Étudiant ajouté avec succès.';
            $messageType = 'success';
            break;
        case 'updated':
            $message = 'Étudiant modifié avec succès.';
            $messageType = 'success';
            break;
        case 'deleted':
            $message = 'Étudiant supprimé avec succès.';
            $messageType = 'success';
            break;
    }
}
if (isset($_GET['error'])) {
    $message = 'Une erreur est survenue. Veuillez réessayer.';
    $messageType = 'error';
}

// Récupérer les filières pour le formulaire
$stmtFilieres = $pdo->query('SELECT id, nom FROM filieres ORDER BY nom');
$filieres = $stmtFilieres->fetchAll();

// Récupérer tous les étudiants avec leur filière (jointure)
$stmtEtudiants = $pdo->query(
    'SELECT e.id, e.nom, e.prenom, f.nom AS filiere
     FROM etudiants e
     INNER JOIN filieres f ON e.filiere_id = f.id
     ORDER BY e.nom, e.prenom'
);
$etudiants = $stmtEtudiants->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav>
    <a href="index.php" class="brand">Gestion<span>Étudiants</span></a>
</nav>

<main>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?>">
            <?= $messageType === 'success' ? '✓' : '✕' ?> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <div class="card">
        <h1 class="page-title">Ajouter un étudiant</h1>

        <form id="form-ajout" action="traitement.php" method="POST" novalidate>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Ex : AHOUNOU" autocomplete="off">
                <span class="field-error" id="nom-error"></span>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" placeholder="Ex : Vital" autocomplete="off">
                <span class="field-error" id="prenom-error"></span>
            </div>

            <div class="form-group">
                <label for="filiere_id">Filière</label>
                <select id="filiere_id" name="filiere_id">
                    <option value="0">— Choisir une filière —</option>
                    <?php foreach ($filieres as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="filiere-error"></span>
            </div>

            <button type="submit" class="btn btn-primary">+ Ajouter</button>
        </form>
    </div>

    <!-- Tableau des étudiants -->
    <div class="card">
        <h2 class="page-title">Liste des étudiants
            <small style="font-size:.75rem;font-weight:400;color:var(--muted);margin-left:.8rem;">
                <?= count($etudiants) ?> enregistré(s)
            </small>
        </h2>

        <?php if (empty($etudiants)): ?>
            <div class="empty">
                <div class="empty-icon">🎓</div>
                <p>Aucun étudiant enregistré pour l'instant.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Filière</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($etudiants as $i => $etudiant): ?>
                            <tr>
                                <td style="color:var(--muted);font-size:.82rem;"><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                                <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                                <td><span class="badge"><?= htmlspecialchars($etudiant['filiere']) ?></span></td>
                                <td>
                                    <div class="actions">
                                        <a href="update.php?id=<?= $etudiant['id'] ?>" class="btn btn-edit">✎ Modifier</a>
                                        <a href="delete.php?id=<?= $etudiant['id'] ?>&nom=<?= urlencode($etudiant['nom']) ?>&prenom=<?= urlencode($etudiant['prenom']) ?>"
                                           class="btn btn-danger"
                                           onclick="return confirmDelete('<?= addslashes($etudiant['nom']) ?>', '<?= addslashes($etudiant['prenom']) ?>')">
                                           ✕ Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</main>

<script src="assets/js/script.js"></script>
</body>
</html>
