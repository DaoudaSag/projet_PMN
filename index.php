<?php 
session_start();
require './backend/db_config.php';

$query = "SELECT id, title, content, created_at FROM blog_posts ORDER BY created_at DESC LIMIT 3";
$stmt = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>École PMN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #carousel {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 70vh; /* Ajuste la hauteur pour centrer le carrousel */
        }

        .carousel-item img {
            max-height: 400px; /* Ajuste la hauteur max */
            width: auto;
            object-fit: contain; /* Assure que l'image ne soit pas coupée */
            margin: auto;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold btn btn-primary text-white" href="#">École PMN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <!-- Liens pour les utilisateurs non connectés -->
                        <li class="nav-item">
                            <a class="nav-link" href="frontend/cours.php">Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="frontend/register.php">Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-success text-white" href="frontend/login.php">Connexion</a>
                        </li>
                    <?php else: ?>
                        <!-- Liens pour les utilisateurs connectés -->
                        <li class="nav-item">
                            <a class="nav-link" href="frontend/cours.php">Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="frontend/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white" href="frontend/logout.php">Déconnexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container text-center mt-5">
        <h1>Bienvenue à l'École PMN</h1>
        <p>Inscrivez-vous pour accéder à nos cours et articles de blog.</p>
        <!-- <a href="frontend/register.php" class="btn btn-primary">S'inscrire</a>
        <a href="frontend/login.php" class="btn btn-secondary">Se connecter</a> -->
    </div>

    <section id="carousel" class="py-5">
        <div class="container">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner text-center">
                    <div class="carousel-item active">
                        <img src="https://img.freepik.com/vecteurs-libre/banniere-developpement-site-web_33099-1687.jpg" 
                            class="d-block img-fluid mx-auto" alt="Formation 1">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Formation en Développement Web</h5>
                            <p>Apprenez les bases du développement web avec HTML, CSS et JavaScript.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://img.freepik.com/vecteurs-libre/illustration-du-concept-optimisation-processus_114360-23833.jpg" 
                            class="d-block img-fluid mx-auto" alt="Formation 2">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Formation en Data Science</h5>
                            <p>Maîtrisez l'analyse de données et le machine learning.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://img.freepik.com/vecteurs-libre/page-destination-marketing-numerique_33099-1726.jpg" 
                            class="d-block img-fluid mx-auto" alt="Formation 3">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Formation en Marketing Digital</h5>
                            <p>Apprenez les stratégies de marketing numérique pour booster votre carrière.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        </div>
    </section>

    <section id="blog" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Derniers articles</h2>
            <div class="row">
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars(substr($row['content'], 0, 100)) ?>...</p>
                                <p class="text-muted"><small>Publié le <?= date("d/m/Y", strtotime($row['created_at'])) ?></small></p>
                                <a href="#" class="btn btn-primary">Lire la suite</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>



<?php include 'frontend/includes/footer.php'; ?>