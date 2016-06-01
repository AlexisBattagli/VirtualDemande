-- Default value of the table 'guacamole_connection'
INSERT INTO `guacamole_db`.`guacamole_connection`(`connection_id`, `connection_name`, `parent_id`, `protocol`, `max_connections`, `max_connections_per_user`) 
VALUES (1,"ssh",NULL,"ssh",NULL,NULL);

-- Default value of the table 'guacamole_user'
INSERT INTO `guacamole_db`.`guacamole_user` (`user_id`, `username`, `password_hash`, `password_salt`, `disabled`, `expired`, `access_window_start`, `access_window_end`, `valid_from`, `valid_until`, `timezone`) 
VALUES (1, 'guacadmin', 'ca458a7d494e3be824f5e1e175a1556c0f8eef2c2d7df3633bec4a29c4411960', 'fe24adc5e11e2b25288d1704abe67a79e342ecc26064ce69c5b3177795a82264', '0', '0', NULL, NULL, NULL, NULL, NULL)
