<?php

/**
 * Description of Guacamole_ConnectionDAL
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once('BaseSingletonGuacamole.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_Connection.php');
require_once('/var/www/VirtualDemande/model/class/Guacamole_Connection.php');

class Guacamole_ConnectionDAL {
    /*
     * Retourne la connexion par défaut
     * 
     * @return Guacamole_Connection
     */
    
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingletonGuacamole::select('SELECT guacamole_connection.connection_id as connection_id, '
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
        $data = BaseSingletonGuacamole::select('SELECT guacamole_connection.connection_id as connection_id, '
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

        $data = BaseSingletonGuacamole::select('SELECT guacamole_connection.connection_id as connection_id, '
                        . 'guacamole_connection.connection_name as connection_name, '
                        . 'guacamole_connection.parent_id as parent_id, '
                        . 'guacamole_connection.protocol as protocol, '
                        . 'guacamole_connection.max_connections as max_connections, '
                        . 'guacamole_connection.max_connections_per_user as max_connections_per_user '
                        . ' FROM guacamole_connection'
                . ' ORDER BY guacamole_connection.parent_id ASC, guacamole_connection.connection_name ASC, guacamole_connection.protocol ASC, guacamole_connection.max_connections ASC, guacamole_connection.max_connections_per_user ASC');

        foreach ($data as $row)
        {
            $guacamoleConnection = new Guacamole_Connection();
            $guacamoleConnection->hydrate($row);
            $mesGuacamoleConnections[] = $guacamoleConnection;
        }

        return $mesGuacamoleConnections;
    }
    
    /*
     * Retourne la Guacamole_Connection correspondant à l'ensemble d'attributs connectionName/protocol/maxConnections/maxConnectionsPerUser
     * Cet ensemble étant unique, il n'y qu'une seule ligne retournée.
     * Il est recherché sans tenir compte de la casse sur connectionName/parentId/protocol/maxConnections/maxConnectionsPerUser
     * 
     * @param string connectionName, string protocol, int maxConnections, int maxConnectionsPerUser
     * @return Guacamole_Connection | null
     */
     public static function findByCP($connectionName, $protocol)
    {
        $data = BaseSingletonGuacamole::select('SELECT guacamole_connection.connection_id as connection_id, '
                        . 'guacamole_connection.connection_name as connection_name, '
                        . 'guacamole_connection.parent_id as parent_id, '
                        . 'guacamole_connection.protocol as protocol, '
                        . 'guacamole_connection.max_connections as max_connections, '
                        . 'guacamole_connection.max_connections_per_user as max_connections_per_user '
                        . ' FROM guacamole_connection'
                        . ' WHERE LOWER(guacamole_connection.connection_name) = LOWER(?) AND LOWER(guacamole_connection.protocol) = LOWER(?)', array('ss', &$connectionName,&$protocol));
        $guacamoleConnection = new Guacamole_Connection();

        if (sizeof($data) > 0)
        {
            $guacamoleConnection->hydrate($data[0]);
        }
        else 
        {
            $guacamoleConnection=null;
        }
         return $guacamoleConnection;
    }
    
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
        //Récupère les valeurs de l'objet guacamoleConnection passé en para de la méthode
        $connectionName=$guacamoleConnection->getConnectionName(); //string
        $parentId=null;
        $parent=$guacamoleConnection->getParent(); //int
        if($parent!=null)
        {
            $parentId=$guacamoleConnection->getParent()->getId(); //int
        }
        $protocol=$guacamoleConnection->getProtocol(); //string
        $maxConnections=$guacamoleConnection->getMaxConnections(); //int
        $maxConnectionsPerUser=$guacamoleConnection->getMaxConnectionsPerUser(); //int
        $connectionId=$guacamoleConnection->getConnectionId(); //int
        if ($connectionId < 0)
        {
            $sql = 'INSERT INTO guacamole_connection (connection_name, parent_id, protocol, max_connections, max_connections_per_user) '
                    . ' VALUES (?,?,?,?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('sisii',
                &$connectionName,
                &$parentId,
                &$protocol,
                &$maxConnections,
                &$maxConnectionsPerUser
            );
        }
        else
        {
            $sql = 'UPDATE guacamole_connection '
                    . 'SET connection_name = ?, '
                    . 'parent_id = ?, '
                    . 'protocol = ?, '
                    . 'max_connections = ?, '
                    . 'max_connections_per_user = ? '
                    . 'WHERE connection_id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('sisiii',
                &$connectionName,
                &$parentId,
                &$protocol,
                &$maxConnections,
                &$maxConnectionsPerUser,
                &$connectionId
            );
        }

        //Exec la requête
        $idInsert = BaseSingletonGuacamole::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime le  Guacamole_Connection correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingletonGuacamole::delete('DELETE FROM guacamole_connection WHERE connection_id = ?', array('i', &$id));
        return $deleted;
    }
}
