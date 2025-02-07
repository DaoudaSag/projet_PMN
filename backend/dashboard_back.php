<?php 
session_start();
require 'db_config.php';

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