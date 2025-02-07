<?php
require '../backend/db_config.php';
require '../backend/cours_back.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .course-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .course-card-body {
            padding: 20px;
        }

        .course-card-body h5 {
            font-size: 1.25rem;
            margin-bottom: 15px;
        }

        .btn-enroll {
            background-color: #2379ff;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-enroll:hover {
            background-color: #1a64d2;
        }

        .no-image-placeholder {
            width: 100%;
            height: 200px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #aaa;
        }
    </style>
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
                            <a class="nav-link" href="#">Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-success text-white" href="login.php">Connexion</a>
                        </li>
                    <?php else: ?>
                        <!-- Liens pour les utilisateurs connectés -->
                        <?php if ($user['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_dashboard.php">Admin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Cours</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-danger text-white" href="logout.php">Déconnexion</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Cours</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.php">Dashboard</a>
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
        <h2 class="text-center mb-4">Explorez nos Cours</h2>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="course-card">
                        <!-- Placehold pour les cours sans image -->
                        <div class="no-image-placeholder">
                            Pas d'image disponible
                        </div>
                        <div class="course-card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($course['description'], 0, 100)); ?>...</p>
                            <a href="enroll.php?course_id=<?= $course['id']; ?>" class="btn-enroll">S'inscrire au cours</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
