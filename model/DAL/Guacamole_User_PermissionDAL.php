<?php

/**
 * Description of Guacamole_User_PermissionDAL
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once('BaseSingletonGuacamole.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_User_Permission.php');

class Guacamole_User_PermissionDAL {
    /*
     * Retourne l'ensemble des Guacamole_User_Permission qui sont en base
     * Lister par Groupe ASC puis Machine ASC
     * 
     * @return array[Guacamole_User_Permission] Tous les Guacamole_User_Permission sont placés dans un Tableau
     */

    public static function findAll()
    {
        $mesguacamoleUserPermissions = array();

        $data = BaseSingleton::select('SELECT guacamole_user_permission.user_id as user_id, '
                        . 'guacamole_user_permission.affected_user_id as affected_user_id, '
                        . 'guacamole_user_permission.permission as permission '
                        . ' FROM guacamole_user_permission'
                        . ' ORDER BY guacamole_user_permission.user_id ASC, guacamole_user_permission.affected_user_id ASC, guacamole_user_permission.permission ASC');

        foreach ($data as $row)
        {
            $guacamoleUserPermission = new Guacamole_User_Permission();
            $guacamoleUserPermission->hydrate($row);
            $mesguacamoleUserPermissions[] = $guacamoleUserPermission;
        }

        return $mesguacamoleUserPermissions;
    }
    
    /*
     * Retourne l'ensemble des Guacamole_User_Permission pour un user_id passée en param
     * 
     * @param int $userId
     * @return  array[Guacamole_User_Permission]
     */

    public static function findByUser($userId)
    {
        $mesguacamoleUserPermissions = array();

        $data = BaseSingleton::select('SELECT guacamole_user_permission.user_id as user_id, '
                        . 'guacamole_user_permission.affected_user_id as affected_user_id, '
                        . 'guacamole_user_permission.permission as permission '
                        . ' FROM guacamole_user_permission'
                        . ' WHERE guacamole_user_permission.user_id = ?', array('i', &$userId));

        foreach ($data as $row)
        {
            $guacamoleUserPermission = new Guacamole_User_Permission();
            $guacamoleUserPermission->hydrate($row);
            $mesguacamoleUserPermissions[] = $guacamoleUserPermission;
        }

        return $mesguacamoleUserPermissions;
    }
    
    /*
     * Retourne l'ensemble des Guacamole_User_Permission pour un affected_user_id passée en param
     * 
     * @param int $affectedUserId
     * @return  array[Guacamole_User_Permission]
     */

    public static function findByAffectedUserId($affectedUserId)
    {
        $mesguacamoleUserPermissions = array();

        $data = BaseSingleton::select('SELECT guacamole_user_permission.user_id as user_id, '
                        . 'guacamole_user_permission.affected_user_id as affected_user_id, '
                        . 'guacamole_user_permission.permission as permission '
                        . ' FROM guacamole_user_permission'
                        . ' WHERE guacamole_user_permission.affected_user_id = ?', array('i', &$affectedUserId));

        foreach ($data as $row)
        {
            $guacamoleUserPermission = new Guacamole_User_Permission();
            $guacamoleUserPermission->hydrate($row);
            $mesguacamoleUserPermissions[] = $guacamoleUserPermission;
        }

        return $mesguacamoleUserPermissions;
    }    
    
    /*
     * Retourne le Guacamole_User_Permission correspondant au couple userId/affectedUserId
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * 
     * @param int userId, int affectedUserId
     * @return Guacamole_User_Permission | null
     */

    public static function findByUA($userId, $affectedUserId)
    {
        $data = BaseSingleton::select('SELECT guacamole_user_permission.user_id as user_id, '
                        . 'guacamole_user_permission.affected_user_id as affected_user_id, '
                        . 'guacamole_user_permission.permission as permission '
                        . ' FROM guacamole_user_permission'
                        . ' WHERE guacamole_user_permission.user_id = ? AND guacamole_user_permission.affected_user_id = ?', array('iss', &$userId, &$affectedUserId));
        $guacamoleUserPermission = new Guacamole_User_Permission();

        if (sizeof($data) > 0)
        {
            $guacamoleUserPermission->hydrate($data[0]);
        }
        else
        {
            $guacamoleUserPermission = null;
        }
        return $guacamoleUserPermission;
    }
    
    /*
     * Insère ou met à jour la Guacamole_User_Permission donnée en paramètre.
     * Pour cela on vérifie si l'id de user_id, affected_user_id et permission transmis sont uniques.
     * Si le couple return null alors il faut insèrer, sinon update aux id transmis.
     * 
     * @param Guacamole_User_Permission $guacamoleUserPermission
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($guacamoleUserPermission)
    {

        //Récupère les valeurs de l'objet Guacamole_User_Permission passé en para de la méthode
        $userId=$guacamoleUserPermission->getUser()->getUserId(); //int
        $affectedUserId=$guacamoleUserPermission->getAffectedUser()->getUserId(); //int
        $permission=$guacamoleUserPermission->getPermission(); //string

        if (is_null(findByCPP($userId, $affectedUserId, $permission)))
        {
            $sql = 'INSERT INTO guacamole_user_permission (user_id, affected_user_id, permission) '
                    . ' VALUES (?,?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('iis',
                &$userId,
                &$affectedUserId,
                &$permission
            );
        }
        else
        {
            $sql = 'UPDATE guacamole_user_permission '
                    . 'SET user_id = ?, '
                    . 'affected_user_id = ?, '
                    . 'permission = ? '
                    . 'WHERE user_id = ? AND affected_user_id = ?';

            //Prépare les info concernant les type de champs
            $params = array('iisii',
                &$userId,
                &$affectedUserId,
                &$permission,
                &$userId,
                &$affectedUserId
            );
        }

        //Exec la requête
        $idInsert = BaseSingletonGuacamole::insertOrEdit($sql, $params);

        return $idInsert;
    }

    /*
     * Supprime la Guacamole_User_Permission correspondant au couple d'id de userId/affectedUserId donné en paramètre
     * 
     * @param int userId, int affectedUserId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($userId, $affectedUserId)
    {
        $deleted = BaseSingletonGuacamole::delete('DELETE FROM guacamole_user_permission WHERE user_id = ? AND affected_user_id = ? AND parameter_value = ?', array('ii', &$userId, &$affectedUserId));
        return $deleted;
    }
}
