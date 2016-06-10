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







