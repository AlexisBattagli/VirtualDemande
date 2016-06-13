CREATE TABLE cpu (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nb_coeur INTEGER UNSIGNED NULL,
  visible BOOLEAN NULL,
  PRIMARY KEY(id)
);

CREATE TABLE distrib (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NULL,
  archi VARCHAR(255) NULL,
  version VARCHAR(255) NULL,
  ihm VARCHAR(255) NULL,
  visible BOOLEAN NULL,
  PRIMARY KEY(id)
);

CREATE TABLE distrib_alias (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  distrib_id INTEGER UNSIGNED NOT NULL,
  nom_complet VARCHAR(255) NULL,
  pseudo VARCHAR(255) NULL,
  commentaire VARCHAR(255) NULL,
  visible BOOLEAN NULL,
  PRIMARY KEY(id)
);

CREATE TABLE groupe (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NULL,
  date_creation DATE NULL,
  description VARCHAR(255) NULL,
  PRIMARY KEY(id)
);

CREATE TABLE groupe_has_machine (
  groupe_id INTEGER UNSIGNED NOT NULL,
  machine_id INTEGER UNSIGNED NOT NULL,
  commentaire TEXT NULL,
  PRIMARY KEY(groupe_id, machine_id)
);

CREATE TABLE limitant (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nb_user_max INTEGER UNSIGNED NULL,
  nb_vm_user INTEGER UNSIGNED NULL,
  PRIMARY KEY(id)
);

CREATE TABLE machine (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  distrib_alias_id INTEGER UNSIGNED NOT NULL,
  utilisateur_id INTEGER UNSIGNED NOT NULL,
  cpu_id INTEGER UNSIGNED NOT NULL,
  ram_id INTEGER UNSIGNED NOT NULL,
  stockage_id INTEGER UNSIGNED NOT NULL,
  nom VARCHAR(255) NULL,
  description VARCHAR(255) NULL,
  date_creation DATE NULL,
  date_expiration DATE NULL,
  etat INTEGER UNSIGNED NULL,
  PRIMARY KEY(id)
);

CREATE TABLE ram (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  valeur INTEGER UNSIGNED NULL,
  visible BOOLEAN NULL,
  PRIMARY KEY(id)
);

CREATE TABLE role (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nom_role VARCHAR(255) NULL,
  description VARCHAR(255) NULL,
  PRIMARY KEY(id)
);

CREATE TABLE stockage (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  valeur INTEGER UNSIGNED NULL,
  visible BOOLEAN NULL,
  PRIMARY KEY(id)
);

CREATE TABLE table_log (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  msg TEXT NULL,
  date_time DATETIME NULL,
  level ENUM('ERROR','INFO','WARN') NULL,
  login_utilisateur TEXT NULL,
  PRIMARY KEY(id)
);

CREATE TABLE utilisateur (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  role_id INTEGER UNSIGNED NOT NULL,
  nom VARCHAR(255) NULL,
  prenom VARCHAR(255) NULL,
  login VARCHAR(255) NULL,
  passwd VARCHAR(255) NULL,
  mail VARCHAR(255) NULL,
  date_creation DATE NULL,
  date_naissance DATE NULL,
  nb_vm INTEGER UNSIGNED NULL,
  PRIMARY KEY(id)
);

CREATE TABLE utilisateur_has_groupe (
  utilisateur_id INTEGER UNSIGNED NOT NULL,
  groupe_id INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(utilisateur_id, groupe_id)
);

-- Default value of the table ‘distrib’
INSERT INTO distrib (id, nom, archi, version, ihm, visible)
VALUE (1, 'default_distrib', '64bits', 'laptop', 'desktop', false); 

-- Default value of the table ‘distrib_alias’
INSERT INTO distrib_alias (id, distrib_id, nom_complet, pseudo, commentaire, visible)
VALUE (1, 1, 'default_complet_name_disitrib', 'default_pseudo_distrib', 'default_commentary', false);

-- Default value of the table ‘limitant’
INSERT INTO limitant (id, nb_user_max, nb_vm_user)
VALUE (1, 10, 3);

-- Default value of the table ‘cpu’
INSERT INTO cpu (id, nb_coeur, visible)
VALUE(1, 0, false );

-- Default value of the table ‘ram’
INSERT INTO ram (id, valeur, visible)
VALUE (1, 0, false);

-- Default value of the table ‘stockage’
INSERT INTO stockage (id, valeur, visible)
VALUE (1, 0, false);

-- Default value of the table ‘role’
INSERT INTO role (id, nom_role, description)
VALUE (1, 'default_role', 'un role');

-- Default value of the table ‘machine’
INSERT INTO machine (id, distrib_alias_id, utilisateur_id, cpu_id, ram_id, stockage_id, nom, description, date_creation, date_expiration,etat)
VALUE (1, 1, 1, 1, 1, 1, 'default_name_machine', 'default_desc_machine', '0000-00-00', '0000-00-00 00:00:00',2);

-- Default value of the table ‘utilisateur’
INSERT INTO utilisateur (id, role_id, nom, prenom, login, passwd, mail, date_creation, date_naissance, nb_vm)
VALUE (1, 1, 'default_firstname', 'default_lastname', 'default_login', 'default_pwd', 'defaultmail@evolve', '0000-00-00', '0000-00-00', 0);

-- Default value of the table ‘groupe’
INSERT INTO groupe (id, nom, date_creation, description)
VALUE (1, 'default_group_name', '0000-00-00', 'default_desc');

-- Debian_jessie_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (4, 'debian', 'amd64', 'jessie', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (4, 'Debian Jessie Graphical 64bits', 'Debian', 'Distrib Debian with release Jessie in 64 bits and graphical version.', true);

-- Debian_jessie_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (5, 'debian', 'i386', 'jessie', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (5, 'Debian Jessie Graphical 32bits', 'Debian', 'Distrib Debian with release Jessie in 32 bits and graphical version.', true);

-- Debian_jessie_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (8, 'debian', 'amd64', 'jessie', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (8, 'Debian Jessie Console 64bits', 'Debian', 'Distrib Debian with release Jessie in 64 bits and console version.', true);

-- Debian_jessie_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (9, 'debian', 'i386', 'jessie', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (9, 'Debian Jessie Console 32bits', 'Debian', 'Distrib Debian with release Jessie in 32 bits and console version.', true);

-- CentOS_6_amd64
-- INSERT INTO distrib (id,nom,archi,version, ihm, visible)
-- VALUE (10, 'centos', 'amd64', '6', 'yes', true);
-- INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
-- VALUE (10, 'CentOS 6 Graphical 64bits', 'CentOS', 'Distrib CentOS with release 6 in 64 bits and graphical version.', true);

-- CentOS_6_i386
-- INSERT INTO distrib (id,nom,archi,version, ihm, visible)
-- VALUE (11, 'centos', 'i386', '6', 'yes', true);
-- INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
-- VALUE (11, 'CentOS 6 Graphical 32bits', 'CentOS', 'Distrib CentOS with release 6 in 32 bits and graphical version.', true);

-- CentOS_7_amd64
-- INSERT INTO distrib (id,nom,archi,version, ihm, visible)
-- VALUE (12, 'centos', 'amd64', '7', 'yes', true);
-- INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
-- VALUE (12, 'CentOS 7 Graphical 64bits', 'CentOS', 'Distrib CentOS with release 7 in 64 bits and graphical version.', true);

-- CentOS_7_i386
-- INSERT INTO distrib (id,nom,archi,version, ihm, visible)
-- VALUE (13, 'centos', 'i386', '7', 'yes', true);
-- INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
-- VALUE (13, 'CentOS 7 Graphic 32bits', 'CentOS', 'Distrib CentOS with release 7 in 32 bits and graphical version.', true);

-- CentOS_6_amd64
-- INSERT INTO distrib (id,nom,archi,version, ihm, visible)
-- VALUE (14, 'centos', 'amd64', '6', 'no', true);
-- INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
-- VALUE (14, 'CentOS 6 Console 64bits', 'CentOS', 'Distrib CentOS with release 6 in 64 bits and console version.', true);

-- CentOS_6_i386
-- INSERT INTO distrib (id,nom,archi,version, ihm, visible)
-- VALUE (15, 'centos', 'i386', '6', 'no', true);
-- INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
-- VALUE (15, 'CentOS 6 Console 32bits', 'CentOS', 'Distrib CentOS with release 6 in 32 bits and console version.', true);

-- CentOS_7_amd64
-- INSERT INTO distrib (id,nom,archi,version, ihm, visible)
-- VALUE (16, 'centos', 'amd64', '7', 'no', true);
-- INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
-- VALUE (16, 'CentOS 7 Console 64bits', 'CentOS', 'Distrib CentOS with release 7 in 64 bits and console version.', true);

-- CentOS_7_i386
-- INSERT INTO distrib (id,nom,archi,version, ihm, visible)
-- VALUE (17, 'centos', 'i386', '7', 'no', true);
-- INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
-- VALUE (17, 'CentOS 7 Console 32bits', 'CentOS', 'Distrib CentOS with release 7 in 32 bits and console version.', true);

-- Ubuntu_wily_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (18, 'ubuntu', 'amd64', 'wily', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (18, 'Ubuntu Wily (15.10) Graphical 64bits', 'Ubuntu', 'Distrib Ubuntu with release Wily (version 15.10) in 64 bits and graphical version.', true);

-- Ubuntu_wily_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (19, 'ubuntu', 'i386', 'wily', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (19, 'Ubuntu Wily (15.10) Graphical 32bits', 'Ubuntu', 'Distrib Ubuntu with release Wily (version 15.10) in 32 bits and graphical version.', true);

-- Ubuntu_xenial_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (20, 'ubuntu', 'amd64', 'xenial', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (20, 'Ubuntu Xenial (16.04 LTS) Graphical 64bits', 'Ubuntu', 'Distrib Ubuntu with release Wily (version 16.04 LTS) in 64 bits and graphical version.', true);

-- Ubuntu_xenial_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (21, 'ubuntu', 'i386', 'xenial', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (21, 'Ubuntu Xenial (16.04 LTS) Graphical 32bits', 'Ubuntu', 'Distrib Ubuntu with release Wily (version 16.04 LTS) in 32 bits and graphical version.', true);

-- Ubuntu_wily_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (22, 'ubuntu', 'amd64', 'wily', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (22, 'Ubuntu Wily (15.10) Console 64bits', 'Ubuntu', 'Distrib Ubuntu with release Wily (version 15.10) in 64 bits and console version.', true);

-- Ubuntu_wily_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (23, 'ubuntu', 'i386', 'wily', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (23, 'Ubuntu Wily (15.10) Console 32bits', 'Ubuntu', 'Distrib Ubuntu with release Wily (version 15.10) in 32 bits and console version.', true);

-- Ubuntu_xenial_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (24, 'ubuntu', 'amd64', 'xenial', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (24, 'Ubuntu Xenial (16.04 LTS) Console 64bits', 'Ubuntu', 'Distrib Ubuntu with release Wily (version 16.04 LTS) in 64 bits and console version.', true);

-- Ubuntu_xenial_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (25, 'ubuntu', 'i386', 'xenial', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (25, 'Ubuntu Xenial (16.04 LTS) Console 32bits', 'Ubuntu', 'Distrib Ubuntu with release Wily (version 16.04 LTS) in 32 bits and console version.', true);

-- Remplissage de la table RAM
INSERT INTO ram (valeur,visible)
VALUE (512, true);
INSERT INTO ram (valeur,visible)
VALUE (1024, true);
INSERT INTO ram (valeur,visible)
VALUE (2048, true);
INSERT INTO ram (valeur,visible)
VALUE (4096, false);
INSERT INTO ram (valeur,visible)
VALUE (8192, false);
INSERT INTO ram (valeur,visible)
VALUE (16384, false);

-- Remplissage de la table CPU
INSERT INTO cpu (nb_coeur,visible)
VALUE (1, true);
INSERT INTO cpu (nb_coeur,visible)
VALUE (2, true);
INSERT INTO cpu (nb_coeur,visible)
VALUE (3, true);
INSERT INTO cpu (nb_coeur,visible)
VALUE (4, false);
INSERT INTO cpu (nb_coeur,visible)
VALUE (6, false);
INSERT INTO cpu (nb_coeur,visible)
VALUE (8, false);
INSERT INTO cpu (nb_coeur,visible)
VALUE (10, false);

-- Remplissage de la table STOCKAGE
INSERT INTO stockage (valeur,visible)
VALUE (6, true);
INSERT INTO stockage (valeur,visible)
VALUE (7, true);
INSERT INTO stockage (valeur,visible)
VALUE (8, true);
INSERT INTO stockage (valeur,visible)
VALUE (9, true);
INSERT INTO stockage (valeur,visible)
VALUE (10, true);
INSERT INTO stockage (valeur,visible)
VALUE (11, true);
INSERT INTO stockage (valeur,visible)
VALUE (20, false);
INSERT INTO stockage (valeur,visible)
VALUE (25, false);
INSERT INTO stockage (valeur,visible)
VALUE (30, false);
INSERT INTO stockage (valeur,visible)
VALUE (50, false);
INSERT INTO stockage (valeur,visible)
VALUE (75, false);
INSERT INTO stockage (valeur,visible)
VALUE (100, false);
INSERT INTO stockage (valeur,visible)
VALUE (120, false);

-- Remplissage de la table role
INSERT INTO role (nom_role, description)
VALUE ('registered','Standard account of EVOLVE Web site.');
INSERT INTO role (nom_role, description)
VALUE ('root', 'Root account of EVOLVE Web site.');

-- Remplissage de la table Utilisateur
INSERT INTO `DBVirtDemande`.`utilisateur` (`role_id` ,`nom` ,`prenom` ,`login` ,`passwd` ,`mail` ,`date_creation` ,`date_naissance` ,`nb_vm`)
VALUES ('2', 'Bourouiba', 'Amine', 'bourouibaa', 'amine', 'Amine.Bourouiba@mines-ales.og', '2016-06-13', '1989-10-09', '0');
INSERT INTO `DBVirtDemande`.`utilisateur` (`role_id` ,`nom` ,`prenom` ,`login` ,`passwd` ,`mail` ,`date_creation` ,`date_naissance` ,`nb_vm`)
VALUES ('3', 'Aurelie', 'GUY', 'aurelie34', 'P@ss', 'aurelie.guy@mines-ales.Fr', '2016-06-11', '1994-04-25', '1');
INSERT INTO `DBVirtDemande`.`utilisateur` (`role_id` ,`nom` ,`prenom` ,`login` ,`passwd` ,`mail` ,`date_creation` ,`date_naissance` ,`nb_vm`)
VALUES ('2', 'Battagli', 'Alexis', 'battaglia', 'alexis', 'alexis.battagli@mines-ales.Fr', '2016-06-12', '1994-03-08', '0');
INSERT INTO `DBVirtDemande`.`utilisateur` (`role_id` ,`nom` ,`prenom` ,`login` ,`passwd` ,`mail` ,`date_creation` ,`date_naissance` ,`nb_vm`)
VALUES ('2', 'Valette', 'Tommy', 'valettet', 'tommy', 'Tommy.Valette@mines-ales.og', '2016-06-13', '1994-10-09', '0');
INSERT INTO `DBVirtDemande`.`utilisateur` (`role_id` ,`nom` ,`prenom` ,`login` ,`passwd` ,`mail` ,`date_creation` ,`date_naissance` ,`nb_vm`)
VALUES ('3', 'Martinez', 'Vincent', 'vincent', 'vincent', 'v.m@live.fr', '2015-02-13', '1990-10-09', '0');

-- Remplissage de la table Groupe
INSERT INTO `DBVirtDemande`.`groupe` (`nom` ,`date_creation` ,`description`)
VALUES ('All', '2016-09-08', "Ce groupe peut intéréssé tout le monde");
INSERT INTO `DBVirtDemande`.`groupe` (`nom` ,`date_creation` ,`description`)
VALUES ('GroupeA+', '2016-06-10', 'Concerne le groupe A+');
INSERT INTO `DBVirtDemande`.`groupe` (`nom` ,`date_creation` ,`description`)
VALUES ('Infres7', '2016-09-08', 'Description : Groupe');

-- Remplissage de la table Machine
INSERT INTO `DBVirtDemande`.`machine` (`distrib_alias_id` ,`utilisateur_id` ,`cpu_id` ,`ram_id` ,`stockage_id` ,`nom` ,`description` ,`date_creation` ,`date_expiration` ,`etat`)
VALUES ('2', '3', '2', '2', '2', 'MachineTest', 'Machine clonÃ© Ã  partir de la machine gree Mot de passe du compte root: ', '2016-06-11', '2017-06-11', '0');
INSERT INTO `DBVirtDemande`.`machine` (`distrib_alias_id` ,`utilisateur_id` ,`cpu_id` ,`ram_id` ,`stockage_id` ,`nom` ,`description` ,`date_creation` ,`date_expiration` ,`etat`)
VALUES ('6','3', '1', '3', '2', 'MachineOne', ' Mot de passe du compte root: ', '2016-06-10', '2017-06-10', '0');
INSERT INTO `DBVirtDemande`.`machine` (`distrib_alias_id` ,`utilisateur_id` ,`cpu_id` ,`ram_id` ,`stockage_id` ,`nom` ,`description` ,`date_creation` ,`date_expiration` ,`etat`)
VALUES ('4', '2', '3', '2', '1', 'MachineGreen', 'Machine clonÃ© Ã  partir de la machine gree Mot de passe du compte root: ', '2016-06-11', '2017-06-11', '0');
INSERT INTO `DBVirtDemande`.`machine` (`distrib_alias_id` ,`utilisateur_id` ,`cpu_id` ,`ram_id` ,`stockage_id` ,`nom` ,`description` ,`date_creation` ,`date_expiration` ,`etat`)
VALUES ('5', '6', '2', '2', '2', 'Machine+', 'MachineAvecBeaucoupDePlus', '2016-04-11', '2017-06-11', '0');

-- Remplissage de la table Groupe_has_Machine
INSERT INTO `groupe_has_machine`(`groupe_id`, `machine_id`, `commentaire`) 
VALUES (2,2,"Machine de test très puissance");
INSERT INTO `groupe_has_machine`(`groupe_id`, `machine_id`, `commentaire`) 
VALUES (1,3,"Machine de test très puissance");
INSERT INTO `groupe_has_machine`(`groupe_id`, `machine_id`, `commentaire`) 
VALUES (4,1,"Machine++");
INSERT INTO `groupe_has_machine`(`groupe_id`, `machine_id`, `commentaire`) 
VALUES (3,4,"Machine de test très puissance");

-- Remplissage de la table Utilisateur_has_Groupe
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (3,1);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (3,2);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (2,2);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (2,4);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (3,4);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (4,4);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (5,4);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (6,3);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (1,4);
INSERT INTO `utilisateur_has_groupe`(`utilisateur_id`, `groupe_id`) 
VALUES (2,3);