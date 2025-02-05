<?php 
session_start();
require '../backend/db_config.php';

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupération des informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupération des cours de l'utilisateur
$stmt = $pdo->prepare("
    SELECT c.* 
    FROM cours c 
    JOIN enrollments e ON c.id = e.cours_id 
    WHERE e.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des articles du blog écrits par l'utilisateur
$stmt = $pdo->prepare("
    SELECT * FROM blog_posts 
    WHERE author_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$blog_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - École PMN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <!-- Section informations utilisateur -->
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h3>Bonjour <?= htmlspecialchars($user['name']) ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informations personnelles</h5>
                                <p>Email : <?= htmlspecialchars($user['email']) ?></p>
                                <p>Rôle : <?= htmlspecialchars($user['role']) ?></p>
                                <p>Date d'inscription : <?= htmlspecialchars(date('d/m/Y à H:i', strtotime($user['created_at']))) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section cours -->
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h3>Mes cours</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($courses): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Date de début</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($course['title']) ?></td>
                                            <td><?= htmlspecialchars(substr($course['description'], 0, 150)) . '...' ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y à H:i', strtotime($course['created_at']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-center">Vous n'êtes inscrit à aucun cours pour le moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section blog -->
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h3>Mes articles de blog</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($blog_posts): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date de publication</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($blog_posts as $post): ?>
                                        <tr>
                                            <td><a href="blog/view.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></td>
                                            <td><?= htmlspecialchars(date('d/m/Y à H:i', strtotime($post['created_at']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-center">Vous n'avez pas encore publié d'article.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton de déconnexion -->
        <div class="text-center">
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>