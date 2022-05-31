--
-- Structure de la table `equipement`
--
CREATE TABLE `equipement` (
  `id` int(11) NOT NULL,
  `ip_machine` varchar(100) DEFAULT NULL,
  `genre` varchar(30) DEFAULT NULL,
  `chariot` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
--
-- Structure de la table `probleme`
--

CREATE TABLE `probleme` (
  `id` int(11) NOT NULL,
  `type_probleme` varchar(100) DEFAULT NULL,
  `id_equipement` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `wms` varchar(30) NOT NULL,
  `date_signalement` date DEFAULT current_timestamp(),
  `expiration_date` varchar(30) DEFAULT NULL,
  `heure_signalement` varchar(30) DEFAULT NULL,
  `process` varchar(30) NOT NULL,
  `localisation` varchar(30) DEFAULT NULL,
  `image` varchar(40) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `del_date` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `pwdreset`
--

CREATE TABLE `pwdreset` (
  `id` int(11) NOT NULL,
  `pwdResetEmail` varchar(70) NOT NULL,
  `pwdResetSelector` varchar(50) NOT NULL,
  `pwdResetToken` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `identifiant` varchar(30) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `site` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- COMPTE PAR DÉFAUT

INSERT INTO `user` (`id`,`nom`, `prenom`, `email`, `identifiant`, `password`, `role`, `site`) VALUES
(1, 'admin', 'admin', 'admin@fmlogistic.com', 'ADMIN', '$2y$10$xa6T6Vkob2qJ2ELGbJPkg.x7ArXlq56G89S0iVOoVqGQyl7BYL82C', 'admin', 'LPO');

--
-- la cles primaire de la table  `equipement`
--
ALTER TABLE `equipement`
  ADD PRIMARY KEY (`id`);

--
-- la cles primaire et les cles etrangaire de la table  `probleme`
--
ALTER TABLE `probleme`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_equipement` (`id_equipement`);

--
-- la cles primaire de la table  `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD PRIMARY KEY (`id`);

--
-- la cles primaire et les donnes uniques de la table  `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identifiant` (`identifiant`),
  ADD UNIQUE KEY `identifiant_2` (`identifiant`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour la table `equipement`
--
ALTER TABLE `equipement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `probleme`
--
ALTER TABLE `probleme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pwdreset`
--
ALTER TABLE `pwdreset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `probleme`
--
ALTER TABLE `probleme`
  ADD CONSTRAINT `probleme_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `probleme_ibfk_2` FOREIGN KEY (`id_equipement`) REFERENCES `equipement` (`id`);
COMMIT;
