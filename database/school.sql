-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 07 fév. 2025 à 20:47
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `school`
--

-- --------------------------------------------------------

--
-- Structure de la table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `content`, `author_id`, `created_at`) VALUES
(1, 'Les bases du développement web', 'Découvrez les fondamentaux du développement web avec HTML, CSS et JavaScript.', 1, '2025-02-07 19:23:32'),
(2, 'L\'importance de la Data Science', 'Pourquoi la data science est essentielle et comment débuter.', 1, '2025-02-07 19:23:32'),
(3, 'L\'évolution du marketing digital', 'Comment le marketing digital a changé ces dernières années.', 1, '2025-02-07 19:23:32');

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `created_at`, `image_url`) VALUES
(1, 'Introduction à PHP', 'Apprenez les bases du langage PHP et la programmation côté serveur.', '2025-02-06 13:56:59', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAATIAAAClCAMAAADoDIG4AAAA5FBMVEV3e7P///8AAABITImustV1ebJyd7Hz8/N5fbWqrM5wdK/CwsLJyt9vc6ZFSYatr8/p6eltca5rb6Nzd6lna6DX19dgZJv4+PuIjLxbX5ff4Oyfn593e6xVWZNMUIw3PIGUmMK4udWAhLiipsw/RIWjp82Xm8SKjrrLy8vX2OeOjo7e3t47Q'),
(2, 'Développement Web avec Symfony', 'Un cours avancé sur le framework Symfony pour créer des applications robustes.', '2025-02-06 13:56:59', NULL),
(3, 'JavaScript et Frontend', 'Comprendre JavaScript moderne, les frameworks et la manipulation du DOM.', '2025-02-06 13:56:59', NULL),
(4, 'Bases de données SQL', 'Maîtrisez la conception, la gestion et l’optimisation des bases de données SQL.', '2025-02-06 13:56:59', NULL),
(5, 'Développement Mobile avec React Native', 'Créez des applications mobiles cross-platform avec React Native.', '2025-02-06 13:56:59', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enrolled_at`) VALUES
(1, 1, 1, '2025-02-06 14:02:09'),
(2, 1, 2, '2025-02-06 14:04:54'),
(3, 1, 4, '2025-02-07 11:40:32'),
(4, 2, 1, '2025-02-07 19:44:36');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'AAA', 'a@a.fr', '$2y$10$fqBUhXYJeZWxFI/nWX/B4eUaglASvSYDPRdD9qMGlJyd7PFAinFgW', 'student', '2025-02-06 13:04:06'),
(2, 'Admin', 'admin@admin.fr', '$2y$10$uqQ8v8n8vgBq5xeerXhX/eRqwncbaQO3oekcCDepP3525omtTYvny', 'admin', '2025-02-06 14:24:14'),
(3, 'Daouda Sagna', 'daouda@sagna.fr', '$2y$10$yYTiK5VIol3UywYcasJlru4F8BR5fpue1DxjXN82JXLnHBGU.7Rrq', 'student', '2025-02-07 20:45:14');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
