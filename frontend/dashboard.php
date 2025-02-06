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
    FROM courses c 
    JOIN enrollments e ON c.id = e.course_id 
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
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold btn btn-primary text-white" href="../index.php">École PMN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <!-- Liens pour les utilisateurs non connectés -->
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Connexion</a>
                        </li>
                    <?php else: ?>
                        <!-- Liens pour les utilisateurs connectés -->
                        <?php if ($user['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_dashboard.php">Admin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="cours.php">Cours</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-danger text-white" href="logout.php">Déconnexion</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="cours.php">Cours</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-danger text-white" href="logout.php">Déconnexion</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-5">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>
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
                                        <th>Date d'inscription</th>
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