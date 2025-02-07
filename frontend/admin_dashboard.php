<?php 
session_start();

require '../backend/db_config.php';

// Récupération de tous les cours
$stmt = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des inscriptions aux cours
$stmt = $pdo->query("SELECT e.id, u.name AS user_name, c.title AS course_title FROM enrollments e JOIN users u ON e.user_id = u.id JOIN courses c ON e.course_id = c.id ORDER BY c.title");
$enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajout d'un cours
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $stmt = $pdo->prepare("INSERT INTO courses (title, description, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$title, $description]);
    $_SESSION['message'] = "Cours ajouté avec succès.";
    $_SESSION['message_type'] = "success";
    header('Location: admin_dashboard.php');
    exit();
}

// Suppression d'un cours
if (isset($_GET['delete_course'])) {
    $course_id = $_GET['delete_course'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $_SESSION['message'] = "Cours supprimé avec succès.";
    $_SESSION['message_type'] = "danger";
    header('Location: admin_dashboard.php');
    exit();
}

// Ajout d'un article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_article'])) {
    $title = $_POST['article_title'];
    $content = $_POST['article_content'];
    $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$title, $content]);
    $_SESSION['message'] = "Article publié avec succès.";
    $_SESSION['message_type'] = "success";
    header('Location: admin_dashboard.php');
    exit();
}

// Récupération de tous les articles
$stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
$blog_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                            <a class="nav-link btn btn-success text-white" href="#">Connexion</a>
                        </li>
                    <?php else: ?>
                        <!-- Liens pour les utilisateurs connectés -->
                        <li class="nav-item">
                            <a class="nav-link" href="admin_dashboard.php">Admin</a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="cours.php">Cours</a>
                            </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white" href="logout.php">Déconnexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Tableau de bord administrateur</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <!-- Formulaire d'ajout de cours -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Ajouter un cours</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <button type="submit" name="add_course" class="btn btn-success">Ajouter</button>
                </form>
            </div>
        </div>

        <!-- Liste des cours -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Liste des cours</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= $course['id']; ?></td>
                                <td><?= htmlspecialchars($course['title']); ?></td>
                                <td><?= htmlspecialchars($course['description']); ?></td>
                                <td>
                                    <a href="admin_dashboard.php?delete_course=<?= $course['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Liste des inscriptions -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Utilisateurs inscrits aux cours</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Cours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrollments as $enrollment): ?>
                            <tr>
                                <td><?= htmlspecialchars($enrollment['user_name']); ?></td>
                                <td><?= htmlspecialchars($enrollment['course_title']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Liste des articles publiés -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Articles publiés</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Contenu</th>
                            <th>Date de publication</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blog_posts as $post): ?>
                            <tr>
                                <td><?= $post['id']; ?></td>
                                <td><?= htmlspecialchars($post['title']); ?></td>
                                <td><?= htmlspecialchars(substr($post['content'], 0, 50)) . '...'; ?></td>
                                <td><?= $post['created_at']; ?></td>
                                <td>
                                    <a href="admin_dashboard.php?delete_article=<?= $post['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Formulaire d'ajout d'article -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Ajouter un article</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="article_title" class="form-label">Titre de l'article</label>
                        <input type="text" name="article_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="article_content" class="form-label">Contenu de l'article</label>
                        <textarea name="article_content" class="form-control" required></textarea>
                    </div>
                    <button type="submit" name="add_article" class="btn btn-success">Publier</button>
                </form>
            </div>
        </div>


        <div class="mt-4 text-center">
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
