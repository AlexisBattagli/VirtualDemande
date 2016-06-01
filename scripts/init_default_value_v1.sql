-- Default value of the table ‘distrib’
INSERT INTO distrib (id, nom, archi, version, ihm, visible)
VALUE (1, 'default_distrib', '64bits', 'laptop', 'desktop', true); 

-- Default value of the table ‘distrib_alias’
INSERT INTO distrib_alias (id, distrib_id, nom_complet, pseudo, commentaire, visible)
VALUE (1, 1, 'default_complet_name_disitrib', 'default_pseudo_distrib', 'default_commentary', true);

-- Default value of the table ‘limitant’
INSERT INTO limitant (id, nb_user_max, nb_vm_user)
VALUE (1, 10, 3);

-- Default value of the table ‘cpu’
INSERT INTO cpu (id, nb_coeur, visible)
VALUE(1, 0, true );

-- Default value of the table ‘ram’
INSERT INTO ram (id, valeur, visible)
VALUE (1, 0, true);

-- Default value of the table ‘stockage’
INSERT INTO stockage (id, valeur, visible)
VALUE (1, 0, true);

-- Default value of the table ‘role’
INSERT INTO role (id, nom_role, description)
VALUE (1, 'default_role', 'un role');

-- Default value of the table ‘machine’
INSERT INTO machine (id, distrib_alias_id, utilisateur_id, cpu_id, ram_id, stockage_id, nom, description, date_creation, date_expiration)
VALUE (1, 1, 1, 1, 1, 1, 'default_name_machine', 'default_desc_machine', '0000-00-00', '0000-00-00 00:00:00' );

-- Default value of the table ‘utilisateur’
INSERT INTO utilisateur (id, role_id, nom, prenom, login, passwd, mail, date_creation, date_naissance, nb_vm)
VALUE (1, 1, 'default_firstname', 'default_lastname', 'default_login', 'default_pwd', 'defaultmail@evolve', '0000-00-00', '0000-00-00', 0);

-- Default value of the table ‘groupe’
INSERT INTO groupe (id, nom, date_creation, description)
VALUE (1, 'default_group_name', '0000-00-00', 'default_desc');

