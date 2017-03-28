-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 28 Mars 2017 à 23:28
-- Version du serveur :  5.7.17-0ubuntu0.16.04.1
-- Version de PHP :  7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `Mini_Blog_Commentaires`
--

CREATE TABLE `Mini_Blog_Commentaires` (
  `id` int(11) NOT NULL,
  `id_billet` int(11) NOT NULL,
  `Auteur` varchar(255) NOT NULL,
  `Contenu` text NOT NULL,
  `Date_commentaire` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Mini_Blog_Commentaires`
--
ALTER TABLE `Mini_Blog_Commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_billet` (`id_billet`),
  ADD KEY `fk_Commentaire_Utilisateur` (`Auteur`);
ALTER TABLE `Mini_Blog_Commentaires` ADD FULLTEXT KEY `fulltext_Auteur` (`Auteur`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Mini_Blog_Commentaires`
--
ALTER TABLE `Mini_Blog_Commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Mini_Blog_Commentaires`
--
ALTER TABLE `Mini_Blog_Commentaires`
  ADD CONSTRAINT `fk_Commentaire_Utilisateur` FOREIGN KEY (`Auteur`) REFERENCES `Mini_Blog_Utilisateur` (`Pseudo`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
