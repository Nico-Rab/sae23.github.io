-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 04 mai 2023 à 14:35
-- Version du serveur :  8.0.33
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ygangnan`
--

-- --------------------------------------------------------

--
-- Structure de la table `Etudiant`
--

CREATE TABLE `Etudiant` (
  `idE` int NOT NULL,
  `Groupe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `DP` int NOT NULL,
  `DS` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Etudiant`
--

INSERT INTO `Etudiant` (`idE`, `Groupe`, `Nom`, `Prenom`, `DP`, `DS`) VALUES
(1, 'GB2', 'Berrada', 'Mehdi', 1, 2),
(2, 'LK2', 'Chevassu', 'Matéo', 3, 2),
(3, 'GB2', 'Cevik', 'Altay', 4, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Lieu`
--

CREATE TABLE `Lieu` (
  `idL` int NOT NULL,
  `Code_Postal` int NOT NULL,
  `Nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nom de la ville'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Lieu`
--

INSERT INTO `Lieu` (`idL`, `Code_Postal`, `Nom`) VALUES
(1, 44000, 'Nantes'),
(2, 25200, 'Montbeliard'),
(3, 25000, 'Besançon'),
(4, 25400, 'Audincourt');

-- --------------------------------------------------------

--
-- Structure de la table `Meteo`
--

CREATE TABLE `Meteo` (
  `IdM` int NOT NULL,
  `IdL` int NOT NULL,
  `FV` decimal(65,30) NOT NULL COMMENT 'Force du Vent',
  `DV` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Direction du Vent',
  `Date` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Etudiant`
--
ALTER TABLE `Etudiant`
  ADD PRIMARY KEY (`idE`),
  ADD KEY `Changement DP` (`DP`),
  ADD KEY `Changement DS` (`DS`);

--
-- Index pour la table `Lieu`
--
ALTER TABLE `Lieu`
  ADD PRIMARY KEY (`idL`);

--
-- Index pour la table `Meteo`
--
ALTER TABLE `Meteo`
  ADD PRIMARY KEY (`IdM`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Etudiant`
--
ALTER TABLE `Etudiant`
  MODIFY `idE` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `Lieu`
--
ALTER TABLE `Lieu`
  MODIFY `idL` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `Meteo`
--
ALTER TABLE `Meteo`
  MODIFY `IdM` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Etudiant`
--
ALTER TABLE `Etudiant`
  ADD CONSTRAINT `Changement DP` FOREIGN KEY (`DP`) REFERENCES `Lieu` (`idL`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Changement DS` FOREIGN KEY (`DS`) REFERENCES `Lieu` (`idL`) ON DELETE SET NULL ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
