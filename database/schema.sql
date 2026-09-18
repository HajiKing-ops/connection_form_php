-- Database structure for the authentication application.

DROP TABLE IF EXISTS `tentativeconnexion`;
CREATE TABLE `tentativeconnexion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `date_tentative` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `login` varchar(50) DEFAULT NULL,
  `email` varchar(80) NOT NULL,
  `mdp` varchar(250) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TRIGGER IF EXISTS `verifier_tentatives`;
DELIMITER ;;
CREATE TRIGGER `verifier_tentatives`
BEFORE INSERT ON `tentativeconnexion`
FOR EACH ROW
BEGIN
    DECLARE nb_tentatives INT;

    SELECT COUNT(*)
    INTO nb_tentatives
    FROM `tentativeconnexion`
    WHERE `login` = NEW.`login`
      AND `date_tentative` >= NOW() - INTERVAL 1 HOUR;

    IF nb_tentatives >= 3 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Maximum 3 tentatives par heure';
    END IF;
END;;
DELIMITER ;
