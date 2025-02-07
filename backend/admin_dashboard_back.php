<?php 
session_start();

require 'db_config.php';

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