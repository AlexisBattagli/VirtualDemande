<?php

/**
 * Description of Guacamole_Connection_PermissionDAL
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once('BaseSingletonGuacamole.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_Connection_Permission.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_Connection.php');


class Guacamole_Connection_PermissionDAL {
    /*
     * Retourne l'ensemble des Guacamole_Connection_Permission qui sont en base
     * Lister par user_id ASC puis connection_id ASC puis permission ASC
     * 
     * @return array[Guacamole_Connection_Permission] Tous les Guacamole_Connection_Permission sont placés dans un Tableau
     */

    public static function findAll()
    {
        $mesGuacamoleConnectionPermissions = array();

        $data = BaseSingleton::select('SELECT guacamole_connection_permission.user_id as user_id, '
                        . 'guacamole_connection_permission.connection_id as connection_id, '
                        . 'guacamole_connection_permission.permission as permission '
                        . ' FROM guacamole_connection_permission'
                        . ' ORDER BY guacamole_connection_permission.user_id ASC, guacamole_connection_permission.connection_id ASC, guacamole_connection_permission.permission ASC');

        foreach ($data as $row)
        {
            $guacamoleConnectionPermission = new Guacamole_Connection_Permission();
            $guacamoleConnectionPermission->hydrate($row);
            $mesGuacamoleConnectionPermissions[] = $guacamoleConnectionPermission;
        }

        return $mesGuacamoleConnectionPermissions;
    }
    
        /*
     * Retourne l'ensemble des Guacamole_Connection_Permission pour une connection_id passée en param
     * 
     * @param int $userId
     * @return  array[Guacamole_Connection_Permission]
     */

    public static function findByUser($userId)
    {
        $mesGuacamoleConnectionPermissions = array();

        $data = BaseSingleton::select('SELECT guacamole_connection_permission.user_id as user_id, '
                        . 'guacamole_connection_permission.connection_id as connection_id, '
                        . 'guacamole_connection_permission.permission as permission '
                        . ' FROM guacamole_connection_permission'
                        . ' WHERE guacamole_connection_permission.user_id = ?', array('i', &$userId));

        foreach ($data as $row)
        {
            $guacamoleConnectionPermission = new Guacamole_Connection_Permission();
            $guacamoleConnectionPermission->hydrate($row);
            $mesGuacamoleConnectionPermissions[] = $guacamoleConnectionPermission;
        }

        return $mesGuacamoleConnectionPermissions;
    }
    
    /*
     * Retourne l'ensemble des Guacamole_Connection_Permission pour une connection_id passée en param
     * 
     * @param int $connectionId
     * @return  array[Guacamole_Connection_Permission]
     */

    public static function findByConnection($connectionId)
    {
        $mesGuacamoleConnectionPermissions = array();

        $data = BaseSingleton::select('SELECT guacamole_connection_permission.user_id as user_id, '
                        . 'guacamole_connection_permission.connection_id as connection_id, '
                        . 'guacamole_connection_permission.permission as permission '
                        . ' FROM guacamole_connection_permission'
                        . ' WHERE guacamole_connection_permission.connection_id = ?', array('i', &$connectionId));

        foreach ($data as $row)
        {
            $guacamoleConnectionPermission = new Guacamole_Connection_Permission();
            $guacamoleConnectionPermission->hydrate($row);
            $mesGuacamoleConnectionPermissions[] = $guacamoleConnectionPermission;
        }

        return $mesGuacamoleConnectionPermissions;
    }
    
    /*
     * Retourne le Guacamole_Connection_Permission correspondant au couple user/connectionId
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * 
     * @param int userId, int connectionId
     * @return Guacamole_Connection_Permission | null
     */

    public static function findByUC($userId, $connectionId)
    {
        $data = BaseSingleton::select('SELECT guacamole_connection_permission.user_id as user_id, '
                        . 'guacamole_connection_permission.connection_id as connection_id, '
                        . 'guacamole_connection_permission.permission as permission '
                        . ' FROM guacamole_connection_permission'
                        . ' WHERE guacamole_connection_permission.user_id = ? AND guacamole_connection_permission.connection_id = ?', array('ii', &$userId, &$connectionId));
        $guacamoleConnectionPermission = new Guacamole_Connection_Permission();

        if (sizeof($data) > 0)
        {
            $guacamoleConnectionPermission->hydrate($data[0]);
        }
        else
        {
            $guacamoleConnectionPermission = null;
        }
        return $guacamoleConnectionPermission;
    }
    
    /*
     * Insère ou met à jour la Guacamole_Connection_Permission donnée en paramètre.
     * Pour cela on vérifie si l'id de user_id, connection_id, parameter_name transmis sont uniques.
     * Si le couple return null alors il faut insèrer, sinon update aux id transmis.
     * 
     * @param Guacamole_Connection_Permission $guacamoleConnectionPermission
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($guacamoleConnectionPermission)
    {

        //Récupère les valeurs de l'objet Guacamole_Connection_Permission passé en para de la méthode
        $userId=$guacamoleConnectionPermission->getUser()->getUserId(); //int
        $connectionId=$guacamoleConnectionPermission->getConnection()->getConnectionId(); //int
        $permission=$guacamoleConnectionPermission->getParameterValue(); //string

        if (is_null(findByUC($userId, $connectionId)))
        {
            $sql = 'INSERT INTO guacamole_connection_permission (user_id, connection_id, permission) '
                    . ' VALUES (?,?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('iis',
                &$userId,
                &$connectionId,
                &$permission
            );
        }
        else
        {
            $sql = 'UPDATE guacamole_connection_permission '
                    . 'SET user_id = ?, '
                    . 'connection_id = ?, '
                    . 'permission = ? '
                    . 'WHERE user_id = ? AND connection_id = ?';

            //Prépare les info concernant les type de champs
            $params = array('iisii',
                &$userId,
                &$connectionId,
                &$permission,
                &$userId,
                &$connectionId
            );
        }

        //Exec la requête
        $idInsert = BaseSingletonGuacamole::insertOrEdit($sql, $params);

        return $idInsert;
    }

    /*
     * Supprime la Guacamole_Connection_Permission correspondant au couple d'id de userId/connectionId donné en paramètre
     * 
     * @param int userId, int connectionId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($userId, $connectionId)
    {
        $deleted = BaseSingletonGuacamole::delete('DELETE FROM guacamole_connection_permission WHERE user_id = ? AND connection_id = ?', array('ii', &$userId, &$connectionId));
        return $deleted;
    }
    
    /*
     * Supprime la Guacamole_Connection_Permission correspondant au couple d'id de userId donné en paramètre
     * 
     * @param int userId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function deleteUser($userId)
    {
        $deleted = BaseSingletonGuacamole::delete('DELETE FROM guacamole_connection_permission WHERE user_id = ?', array('i', &$userId));
        return $deleted;
    }
    
    /*
     * Supprime la Guacamole_Connection_Permission correspondant à connectionId donné en paramètre
     * 
     * @param int connectionId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function deleteConnection($connectionId)
    {
        $deleted = BaseSingletonGuacamole::delete('DELETE FROM guacamole_connection_permission WHERE connection_id = ?', array('i', &$connectionId));
        return $deleted;
    }
}
