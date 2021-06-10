-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 10 juin 2021 à 09:54
-- Version du serveur :  10.3.29-MariaDB-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `PPE3`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomCategorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionCategorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nomCategorie`, `descriptionCategorie`, `created_at`, `updated_at`) VALUES
(1, 'Utilisateur', 'Toute personne ayant uniquement accès à la base des fournitures pour établir sa liste.', '2020-12-09 08:45:22', '2020-12-09 08:45:22'),
(2, 'Valideur', 'La personne en charge de confirmer la demande auprès des SG.', '2020-12-09 08:45:22', '2020-12-09 08:45:22'),
(3, 'Administrateur', 'Accès à toute la base et paramètre de l’outil et gestion des stocks', '2020-12-09 08:45:22', '2020-12-09 08:45:22');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idEtat` int(11) NOT NULL,
  `idFournitures` int(11) NOT NULL,
  `idPersonnel` int(11) NOT NULL,
  `nomCommandes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantiteDemande` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `idEtat`, `idFournitures`, `idPersonnel`, `nomCommandes`, `quantiteDemande`, `created_at`, `updated_at`) VALUES
(1, 3, 5, 2, 'Lot de 4 stylo bic cristal', 1, '2020-12-21 12:04:04', '2021-04-19 13:16:52'),
(2, 4, 3, 2, 'Stylo bic cristal rouge', 2, '2020-12-14 13:37:16', '2021-12-20 08:21:54'),
(3, 2, 8, 3, 'Papier d\'imprimante Clairefontaine', 3, '2020-12-28 12:56:32', '2021-04-17 13:56:16'),
(4, 1, 8, 2, 'Papier d\'imprimante Clairefontaine', 5, '2021-04-20 09:30:04', '2021-04-20 09:30:04');

-- --------------------------------------------------------

--
-- Structure de la table `demandes_specifiques`
--

CREATE TABLE `demandes_specifiques` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idEtat` int(11) NOT NULL,
  `idPersonnel` int(11) NOT NULL,
  `nomDemande` varchar(255) NOT NULL,
  `quantiteDemande` int(11) NOT NULL,
  `lienProduit` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demandes_specifiques`
--

INSERT INTO `demandes_specifiques` (`id`, `idEtat`, `idPersonnel`, `nomDemande`, `quantiteDemande`, `lienProduit`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Ciseau de bureau', 1, 'https://dactylbureau-calipage.fournituredebureau.com/Detail.aspx?ProductId=4315838', '2020-12-09 07:33:41', '2021-04-19 13:06:59'),
(2, 3, 2, 'Stick de colle UHU', 3, 'https://dactylbureau-calipage.fournituredebureau.com/Detail.aspx?ProductId=59108', '2020-12-22 11:04:37', '2021-12-22 13:27:17'),
(3, 1, 2, 'Vidéo projecteur', 1, 'Aucun lien fourni', '2020-12-24 16:41:17', '2021-04-17 13:35:49');

-- --------------------------------------------------------

--
-- Structure de la table `etats`
--

CREATE TABLE `etats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomEtat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionEtat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etats`
--

INSERT INTO `etats` (`id`, `nomEtat`, `descriptionEtat`, `created_at`, `updated_at`) VALUES
(1, 'Prise en compte', 'La commande à été prise en compte.', '2020-12-09 08:45:22', '2020-12-09 08:45:22'),
(2, 'Validé', 'La commande à été validé.', '2020-12-09 08:45:22', '2020-12-09 08:45:22'),
(3, 'En cours', 'La commande est en cours.', '2020-12-09 08:45:22', '2020-12-09 08:45:22'),
(4, 'Livré', 'La commande est livré.', '2020-12-09 08:45:22', '2020-12-09 08:45:22'),
(5, 'Annulé', 'La commande à été annulé.', '2020-12-09 08:45:22', '2020-12-09 08:45:22');

-- --------------------------------------------------------

--
-- Structure de la table `familles_fournitures`
--

CREATE TABLE `familles_fournitures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomFamille` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionFamille` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `familles_fournitures`
--

INSERT INTO `familles_fournitures` (`id`, `nomFamille`, `descriptionFamille`, `created_at`, `updated_at`) VALUES
(1, 'Aucune', 'L\'article n\'appartient à aucune familles', '2021-01-03 13:58:14', '2021-01-03 13:58:14'),
(2, 'Stylos bille', 'Tous les stylos à bille.', '2021-01-03 14:00:34', '2021-01-03 14:00:34'),
(3, 'Feuilles d\'imprimante', 'Toutes les feuilles d\'imprimante', '2021-01-03 14:01:23', '2021-01-03 14:01:23'),
(4, 'Feuilles à dessin', 'Toutes les feuilles pour le dessin', '2021-01-06 14:17:52', '2021-01-06 14:17:52');

-- --------------------------------------------------------

--
-- Structure de la table `fournitures`
--

CREATE TABLE `fournitures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idFamille` int(11) NOT NULL,
  `nomFournitures` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomPhoto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionFournitures` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantiteDisponible` int(11) NOT NULL,
  `quantiteMinimum` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fournitures`
--

INSERT INTO `fournitures` (`id`, `idFamille`, `nomFournitures`, `nomPhoto`, `descriptionFournitures`, `quantiteDisponible`, `quantiteMinimum`, `created_at`, `updated_at`) VALUES
(1, 2, 'Stylo bic cristal bleu', 'stylo-bic-cristal-bleu', 'Stylo bic cristal bleu', 10, 12, '2020-12-10 12:22:28', '2021-06-10 07:36:13'),
(2, 2, 'Stylo bic cristal vert', 'stylo-bic-cristal-vert', 'Stylo bic cristal vert', 6, 0, '2020-12-10 12:22:28', '2021-03-24 09:09:10'),
(3, 2, 'Stylo bic cristal rouge', 'stylo-bic-cristal-rouge', 'Stylo bic cristal rouge', 8, 0, '2020-12-10 12:22:28', '2021-03-24 08:49:01'),
(4, 1, 'Stylo bic cristal noir', 'stylo-bic-cristal-noir', 'Stylo bic cristal noir', 3, 0, '2020-12-10 12:22:28', '2020-12-10 12:22:28'),
(5, 2, 'Lot de 4 stylo bic cristal', 'lot-stylo-bic-cristal', 'Lot de 4 stylo bic cristal', 7, 0, '2020-12-10 12:22:28', '2020-12-10 12:22:28'),
(6, 2, 'Stylo bic quatre couleurs', 'stylo-bic-quatre-couleurs', 'Stylo bic quatre couleurs', 16, 0, '2020-12-10 12:22:28', '2021-03-24 09:18:48'),
(7, 1, 'Ciseaux Maped', 'ciseaux-maped', 'Ciseaux de la marque Maped', 11, 0, '2020-12-27 11:47:04', '2020-12-27 11:47:04'),
(8, 3, 'Papier d\'imprimante Clairefontaine', 'papier-d\'imprimante-clairefontaine', 'Papier d\'imprimante Clairefontaine', 18, 0, '2020-12-27 12:00:27', '2021-04-20 10:03:51'),
(9, 1, 'Papier canson', 'papier-canson', 'Papier de la marque canson', 10, 0, '2020-12-29 15:37:47', '2021-01-03 15:36:28'),
(10, 1, 'Stic de colle UHU', 'stic-de-colle-uhu', 'Stic de colle UHU', 6, 5, '2021-06-10 07:29:14', '2021-06-10 07:36:04');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2020_10_28_144756_create_commandes_table', 1),
(2, '2020_10_28_144825_create_fournitures_table', 1),
(3, '2020_10_28_144851_create_personnel_table', 1),
(4, '2020_11_14_092922_create_service_table', 1),
(5, '2020_11_14_092948_create_categorie_table', 1),
(6, '2020_11_14_093006_create_etat_table', 1),
(7, '2020_12_21_163451_create_demandes_specifiques_table', 1),
(8, '2021_01_03_202625_create_familles_fournitures_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `personnels`
--

CREATE TABLE `personnels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idCategorie` int(11) NOT NULL,
  `idService` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(1500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personnels`
--

INSERT INTO `personnels` (`id`, `idCategorie`, `idService`, `nom`, `prenom`, `mail`, `pass`, `message`, `created_at`, `updated_at`) VALUES
(1, 3, 3, 'Admin', 'Admin', 'admin@admin.fr', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'Bienvenue à la CCI d\'Alsace Eurométropole !', '2020-12-05 12:51:03', '2021-03-24 17:11:02'),
(2, 1, 2, 'Utilisateur', 'Comptabilité', 'utilisateur@comptabilite.fr', 'dd10ddc914f2528f71534cfbf6d73a9fadd3661efb09ae6d855c2d73fa81cc6b', 'Bienvenue à la CCI d\'Alsace Eurométropole !', '2020-12-06 09:32:17', '2021-04-20 10:15:08'),
(3, 2, 2, 'Valideur', 'Comptabilité', 'valideur@comptabilite.fr', '047ebb570b073adbfa9902e16727859f38309851bdcc000ac69efccde471858b', 'Bienvenue à la CCI d\'Alsace Eurométropole !', '2021-01-02 12:00:25', '2021-03-23 18:24:12'),
(4, 1, 1, 'Utilisateur', 'Accueil', 'utilisateur@accueil.fr', 'dd10ddc914f2528f71534cfbf6d73a9fadd3661efb09ae6d855c2d73fa81cc6b', 'Bienvenue à la CCI d\'Alsace Eurométropole !', '2021-01-02 11:58:44', '2021-03-23 18:24:12'),
(5, 2, 1, 'Valideur', 'Accueil', 'valideur@accueil.fr', '047ebb570b073adbfa9902e16727859f38309851bdcc000ac69efccde471858b', 'Bienvenue à la CCI d\'Alsace Eurométropole !', '2020-12-21 08:26:58', '2021-03-23 18:24:12'),
(6, 1, 3, 'Utilisateur', 'Administration', 'utilisateur@administration.fr', 'dd10ddc914f2528f71534cfbf6d73a9fadd3661efb09ae6d855c2d73fa81cc6b', 'Bienvenue à la CCI d\'Alsace Eurométropole !', '2021-01-02 12:01:58', '2021-03-23 18:24:12'),
(7, 2, 3, 'Valideur', 'Administration', 'valideur@administration.fr', '047ebb570b073adbfa9902e16727859f38309851bdcc000ac69efccde471858b', 'Bienvenue à la CCI d\'Alsace Eurométropole !', '2021-01-02 12:03:00', '2021-03-23 18:24:12'),
(8, 1, 4, 'Utilisateur', 'Apprentissage', 'utilisateur@apprentissage.fr', 'dd10ddc914f2528f71534cfbf6d73a9fadd3661efb09ae6d855c2d73fa81cc6b', 'Bienvenue à la CCI d\'Alsace Eurométropole !', '2021-03-23 07:40:43', '2021-03-23 18:24:12');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomService` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionService` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `nomService`, `descriptionService`, `created_at`, `updated_at`) VALUES
(1, 'Accueil', 'Le service d\'accueil de la CCI.', '2020-12-09 08:45:22', '2020-12-09 08:45:22'),
(2, 'Comptabilité', 'Le service de comptabilité de la CCI.', '2020-12-09 08:45:22', '2020-12-09 08:45:22'),
(3, 'Administration', 'Le service d\'administration de la CCI.', '2020-12-09 07:45:22', '2020-12-09 07:45:22'),
(4, 'Apprentissage', 'Le service d\'apprentissage de la CCI.', '2021-01-02 13:30:09', '2021-01-02 13:30:09');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demandes_specifiques`
--
ALTER TABLE `demandes_specifiques`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etats`
--
ALTER TABLE `etats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `familles_fournitures`
--
ALTER TABLE `familles_fournitures`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fournitures`
--
ALTER TABLE `fournitures`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `personnels`
--
ALTER TABLE `personnels`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `demandes_specifiques`
--
ALTER TABLE `demandes_specifiques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `etats`
--
ALTER TABLE `etats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `familles_fournitures`
--
ALTER TABLE `familles_fournitures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `fournitures`
--
ALTER TABLE `fournitures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `personnels`
--
ALTER TABLE `personnels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
