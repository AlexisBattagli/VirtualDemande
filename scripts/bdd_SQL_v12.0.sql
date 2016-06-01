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
  PRIMARY KEY(id),
  INDEX distrib_alias_FKIndex1(distrib_id)
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
  commentaire VARCHAR(255) NULL,
  INDEX groupe_has_machine_FKIndex1(machine_id),
  INDEX groupe_has_machine_FKIndex2(groupe_id)
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
  PRIMARY KEY(id),
  INDEX machine_FKIndex3(stockage_id),
  INDEX machine_FKIndex4(ram_id),
  INDEX machine_FKIndex5(cpu_id),
  INDEX machine_FKIndex6(utilisateur_id),
  INDEX machine_FKIndex7(distrib_alias_id)
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
  PRIMARY KEY(id),
  INDEX utilisateur_FKIndex1(role_id)
);

CREATE TABLE utilisateur_has_groupe (
  utilisateur_id INTEGER UNSIGNED NOT NULL,
  role_groupe VARCHAR(255) NULL,
  INDEX utilisateur_has_groupe_FKIndex1(utilisateur_id)
);


