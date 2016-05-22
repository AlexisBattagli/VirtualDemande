<?php

/**
 * Description of Guacamole_ConnectionDAL
 *
 * @author Alexis
 * @author Aurelie
 */

//import


class Guacamole_ConnectionDAL {
    /*
     * Retourne la connexion par défaut
     * 
     * @return Guacamole_Connection
     */
    
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT guacamole_connection.connection_id as connection_id, '
                        . 'guacamole_connection.connection_name as connection_name, '
                        . 'guacamole_connection.parent_id as parent_id, '
                        . 'guacamole_connection.protocol as protocol, '
                        . 'guacamole_connection.max_connections as max_connections, '
                        . 'guacamole_connection.max_connections_per_user as max_connections_per_user '
                        . ' FROM guacamole_connection'
                        . ' WHERE guacamole_connection.connection_id = ?', array('i', &$id));
        $guacamoleConnection = new Guacamole_Connection();
        if (sizeof($data) > 0)
        {
            $guacamoleConnection->hydrate($data[0]);
        }
        else
        {
            $guacamoleConnection = null;
        }
        return $guacamoleConnection;
    }
    
    /*
     * Retourne la connexion correspondant à l'id donné
     *      
     * @param int $id
     * @return Guacamole_Connection
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT guacamole_connection.connection_id as connection_id, '
                        . 'guacamole_connection.connection_name as connection_name, '
                        . 'guacamole_connection.parent_id as parent_id, '
                        . 'guacamole_connection.protocol as protocol, '
                        . 'guacamole_connection.max_connections as max_connections, '
                        . 'guacamole_connection.max_connections_per_user as max_connections_per_user '
                        . ' FROM guacamole_connection'
                        . ' WHERE guacamole_connection.connection_id = ?', array('i', &$id));
        $guacamoleConnection = new Guacamole_Connection();
        if (sizeof($data) > 0)
        {
            $guacamoleConnection->hydrate($data[0]);
        }
        else
        {
            $guacamoleConnection = null;
        }
        return $guacamoleConnection;
    }
    
    /*
     * Retourne l'ensemble des connexions qui sont en base
     * 
     * @return array[Guacamole_Connection] Tous les Guacamole_Connection sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesGuacamoleConnections = array();

        $data = BaseSingleton::select('SELECT guacamole_connection.connection_id as connection_id, '
                        . 'guacamole_connection.connection_name as connection_name, '
                        . 'guacamole_connection.parent_id as parent_id, '
                        . 'guacamole_connection.protocol as protocol, '
                        . 'guacamole_connection.max_connections as max_connections, '
                        . 'guacamole_connection.max_connections_per_user as max_connections_per_user '
                        . ' FROM guacamole_connection'
                . ' ORDER BY guacamole_connection.parent_id ASC, guacamole_connection.connection_name ASC, guacamole_connection.protocol ASC, guacamole_connection.max_connections ASC, guacamole_connection.max_connections_per_user ASC');

        foreach ($data as $row)
        {
            $guacamoleConnection = new Cpu();
            $guacamoleConnection->hydrate($row);
            $mesGuacamoleConnections[] = $guacamoleConnection;
        }

        return $mesGuacamoleConnections;
    }
    
    //Voir ligne renvoyant un seule ligne
    
    /*
     * Insère ou met à jour le Guacamole_Connection donné en paramètre.
     * Pour cela on vérifie si l'id du Guacamole_Connection transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Guacamole_Connection guacamoleConnection
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($guacamoleConnection)
    {

        //Récupère les valeurs de l'objet cpu passé en para de la méthode
        $nbCoeur = $cpu->getNbCoeur(); //int
        $visible = $cpu->getVisible(); //bool
        $id = $cpu->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO cpu (nb_coeur, visible) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ib',
                &$nbCoeur,
                &$visible
            );
        }
        else
        {
            $sql = 'UPDATE cpu '
                    . 'SET nb_coeur = ?, '
                    . 'visible = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('ibi',
                &$nbCoeur,
                &$visible,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
}
