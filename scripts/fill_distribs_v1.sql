-- Debian_sid_amd64 
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (2, 'debian', 'amd64', 'sid', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (2, 'Debian Sid Graphical 64bits', 'Debian', 'Distrib Debian with release Sid in 64 bits and graphical version.', true);

-- Debian_sid_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (3, 'debian', 'i386', 'sid', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (3, 'Debian Sid Graphical 32bits', 'Debian', 'Distrib Debian with release Sid in 32 bits and graphical version.', true);

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

-- Debian_sid_amd64 
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (6, 'debian', 'amd64', 'sid', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (6, 'Debian Sid Console 64bits', 'Debian', 'Distrib Debian with release Sid in 64 bits and console version.', true);

-- Debian_sid_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (7, 'debian', 'i386', 'sid', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (7, 'Debian Sid Console 32bits', 'Debian', 'Distrib Debian with release Sid in 32 bits and console version.', true);

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
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (10, 'centos', 'amd64', '6', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (10, 'CentOS 6 Graphical 64bits', 'CentOS', 'Distrib CentOS with release 6 in 64 bits and graphical version.', true);

-- CentOS_6_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (11, 'centos', 'i386', '6', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (11, 'CentOS 6 Graphical 32bits', 'CentOS', 'Distrib CentOS with release 6 in 32 bits and graphical version.', true);

-- CentOS_7_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (12, 'centos', 'amd64', '7', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (12, 'CentOS 7 Graphical 64bits', 'CentOS', 'Distrib CentOS with release 7 in 64 bits and graphical version.', true);

-- CentOS_7_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (13, 'centos', 'i386', '7', 'yes', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (13, 'CentOS 7 Graphic 32bits', 'CentOS', 'Distrib CentOS with release 7 in 32 bits and graphical version.', true);

-- CentOS_6_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (14, 'centos', 'amd64', '6', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (14, 'CentOS 6 Console 64bits', 'CentOS', 'Distrib CentOS with release 6 in 64 bits and console version.', true);

-- CentOS_6_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (15, 'centos', 'i386', '6', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (15, 'CentOS 6 Console 32bits', 'CentOS', 'Distrib CentOS with release 6 in 32 bits and console version.', true);

-- CentOS_7_amd64
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (16, 'centos', 'amd64', '7', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (16, 'CentOS 7 Console 64bits', 'CentOS', 'Distrib CentOS with release 7 in 64 bits and console version.', true);

-- CentOS_7_i386
INSERT INTO distrib (id,nom,archi,version, ihm, visible)
VALUE (17, 'centos', 'i386', '7', 'no', true);
INSERT INTO distrib_alias (distrib_id,nom_complet,pseudo,commentaire,visible)
VALUE (17, 'CentOS 7 Console 32bits', 'CentOS', 'Distrib CentOS with release 7 in 32 bits and console version.', true);

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


