<?php
session_start();
require '../backend/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    $user_id = $_SESSION['user_id'];

    // Vérifier si l'utilisateur est déjà inscrit
    $stmt = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['message'] = "Vous êtes déjà inscrit à ce cours.";
        $_SESSION['message_type'] = "warning";
    } else {
        // Inscrire l'utilisateur
        $stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $course_id])) {
            $_SESSION['message'] = "Inscription réussie au cours.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Une erreur est survenue.";
            $_SESSION['message_type'] = "danger";
        }
    }

    header("Location: dashboard.php");
    exit();
}
?>
