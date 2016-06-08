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
  date_expiration DATETIME NULL,
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
  table_log_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  date_heure DATETIME NULL,
  action TEXT NULL,
  code_retour TEXT NULL,
  utilisateur TEXT NULL,
  machine TEXT NULL,
  PRIMARY KEY(table_log_id)
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


