-- Remplissage de la table limitant
INSERT INTO limitant (nb_user_max, nb_vm_user)
VALUE (6,2);

-- Remplissage de la table role
INSERT INTO role (nom_role, description)
VALUE ('registered','Standard account of EVOLVE Web site.');
INSERT INTO role (nom_role, description)
VALUE ('root', 'Root account of EVOLVE Web site.');
