<?php
session_start();
require 'db_config.php';

// Vérifier si la connexion est bien établie
if (!isset($pdo)) {
    die("Erreur de connexion à la base de données.");
}

// Récupérer les informations de l'utilisateur s'il est connecté
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupérer les cours depuis la base de données
try {
    $sql = "SELECT * FROM courses";
    $stmt = $pdo->query($sql);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des cours : " . $e->getMessage());
}
?>