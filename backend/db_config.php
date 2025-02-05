<?php
$host = 'localhost';    // Hôte de la BDD
$dbname = 'school';     // Nom de la BDD
$username = 'root';     // Nom d'utilisateur (par défaut pour XAMPP)
$password = 'root';         // Mot de passe (vide pour XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
