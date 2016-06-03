<?php

/**
 * Description of Guacamole_UserDAL
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once('BaseSingletonGuacamole.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_User.php');
require_once('/var/www/VirtualDemande/model/class/Guacamole_User.php');

class Guacamole_UserDAL {
    /*
     * Retourne l'utilisateur par défaut
     * 
     * @return Guacamole_User
     */
    
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT guacamole_user.user_id as user_id, '
                        . 'guacamole_user.username as username, '
                        . 'guacamole_user.password_hash as password_hash, '
                        . 'guacamole_user.password_salt as password_salt, '
                        . 'guacamole_user.disabled as disabled, '
                        . 'guacamole_user.expired as expired, '
                        . 'guacamole_user.access_window_start as access_window_start, '
                        . 'guacamole_user.access_window_end as access_window_end, '
                        . 'guacamole_user.valid_from as valid_from, '
                        . 'guacamole_user.valid_until as valid_until, '
                        . 'guacamole_user.timezone as timezone '
                        . ' FROM guacamole_user'
                        . ' WHERE guacamole_user.user_id = ?', array('i', &$id));
        $guacamoleUser= new Guacamole_User();
        if (sizeof($data) > 0)
        {
            $guacamoleUser->hydrate($data[0]);
        }
        else
        {
            $guacamoleUser = null;
        }
        return $guacamoleUser;
    }
    
    /*
     * Retourne l'utilisateur correspondant à l'id donné
     * 
     * @param int $id
     * @return Guacamole_User
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT guacamole_user.user_id as user_id, '
                        . 'guacamole_user.username as username, '
                        . 'guacamole_user.password_hash as password_hash, '
                        . 'guacamole_user.password_salt as password_salt, '
                        . 'guacamole_user.disabled as disabled, '
                        . 'guacamole_user.expired as expired, '
                        . 'guacamole_user.access_window_start as access_window_start, '
                        . 'guacamole_user.access_window_end as access_window_end, '
                        . 'guacamole_user.valid_from as valid_from, '
                        . 'guacamole_user.valid_until as valid_until, '
                        . 'guacamole_user.timezone as timezone '
                        . ' FROM guacamole_user'
                        . ' WHERE guacamole_user.user_id = ?', array('i', &$id));
        $guacamoleUser = new Guacamole_User();
        if (sizeof($data) > 0)
        {
            $guacamoleUser->hydrate($data[0]);
        }
        else
        {
            $guacamoleUser = null;
        }
        return $guacamoleUser;
    }
    
    /*
     * Retourne l'ensemble des utilisateurs qui sont en base
     * 
     * @return array[Guacamole_User] Tous les Guacamole_User sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesGuacamoleUsers = array();

        $data = BaseSingleton::select('SELECT guacamole_user.user_id as user_id, '
                        . 'guacamole_user.username as username, '
                        . 'guacamole_user.password_hash as password_hash, '
                        . 'guacamole_user.password_salt as password_salt, '
                        . 'guacamole_user.disabled as disabled, '
                        . 'guacamole_user.expired as expired, '
                        . 'guacamole_user.access_window_start as access_window_start, '
                        . 'guacamole_user.access_window_end as access_window_end, '
                        . 'guacamole_user.valid_from as valid_from, '
                        . 'guacamole_user.valid_until as valid_until, '
                        . 'guacamole_user.timezone as timezone '
                        . ' FROM guacamole_user'
                . ' ORDER BY guacamole_user.username ASC');

        foreach ($data as $row)
        {
            $guacamoleUser = new Guacamole_User();
            $guacamoleUser->hydrate($row);
            $mesGuacamoleUsers[] = $guacamoleUser;
        }

        return $mesGuacamoleUsers;
    }
    
    /*
     * Retourne la Guacamole_User correspondant à l'ensemble d'attributs username
     * Cet ensemble étant unique, il n'y qu'une seule ligne retournée.
     * Il est recherché sans tenir compte de la casse sur username
     * 
     * @param string username
     * @return Guacamole_User | null
     */
     public static function findByUsername($username)
    {
        $data = BaseSingleton::select('SELECT guacamole_user.user_id as user_id, '
                        . 'guacamole_user.username as username, '
                        . 'guacamole_user.password_hash as password_hash, '
                        . 'guacamole_user.password_salt as password_salt, '
                        . 'guacamole_user.disabled as disabled, '
                        . 'guacamole_user.expired as expired, '
                        . 'guacamole_user.access_window_start as access_window_start, '
                        . 'guacamole_user.access_window_end as access_window_end, '
                        . 'guacamole_user.valid_from as valid_from, '
                        . 'guacamole_user.valid_until as valid_until, '
                        . 'guacamole_user.timezone as timezone '
                        . ' FROM guacamole_user'
                        . ' WHERE LOWER(guacamole_user.username) = LOWER(?)', array('s', &$username));
        $guacamoleUser = new Guacamole_User();

        if (sizeof($data) > 0)
        {
            $guacamoleUser->hydrate($data[0]);
        }
        else 
        {
            $guacamoleUser=null;
        }
         return $guacamoleUser;
    }
    
    /*
     * Insère ou met à jour le Guacamole_User donné en paramètre.
     * Pour cela on vérifie si l'id du Guacamole_User transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Guacamole_User guacamoleUser
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($guacamoleUser)
    {
        //Récupère les valeurs de l'objet guacamoleConnection passé en para de la méthode
        $username = $guacamoleUser->getUsername(); //string
        $passwordHash = $guacamoleUser->getPasswordHash(); //string
        $passwordSalt = $guacamoleUser->getPasswordSalt(); //string
        $disabled = $guacamoleUser->getDisabled(); //int
        $expired = $guacamoleUser->getExpired(); //int
        $accessWindowStart = $guacamoleUser->getAccessWindowStart(); //string
        $accessWindowEnd = $guacamoleUser->getAccessWindowEnd(); //string
        $validFrom = $guacamoleUser->getValidFrom(); //string
        $validUntil = $guacamoleUser->getValidUntil(); //string
        $timezone = $guacamoleUser->getTimezone(); //string
        $userId = $guacamoleUser->getUserId(); //int
      
        if ($userId < 0)
        {
            $sql = 'SET @salt = UNHEX(SHA2(UUID(), 256));'
                    . ' INSERT INTO guacamole_user (username, password_hash, password_salt, disabled, expired, access_window_start, access_window_end, valid_from, valid_until, timezone) '
                    . ' VALUES (?,UNHEX(SHA2(CONCAT(?, HEX(@salt)), 256)), HEX(@salt)), 256)),@salt,?,?,?,?,?,?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ssiisssss',
                &$username,
                &$passwordHash,
                &$disabled,
                &$expired,
                &$accessWindowStart,
                &$accessWindowEnd,
                &$validFrom,
                &$validUntil,
                &$timezone
            );
        }
        else
        {
            /*
             SET @salt = UNHEX(SHA2(UUID(), 256));
UPDATE guacamole_user 
                    SET username = "a",
                    password_hash = UNHEX(SHA2(CONCAT("mypass", HEX(@salt)), 256)),
                    password_salt = @salt,
                    disabled = 0, 
                    expired = 0, 
                    access_window_start = 0000/00/00, 
                    access_window_end = 0000/00/00, 
                    valid_from = NULL, 
                    valid_until = NULL,
                    timezone = "OK"
                    WHERE user_id = 10;
             */
            
            
            
            $sql = 'SET @salt = UNHEX(SHA2(UUID(), 256));'
                    . ' UPDATE guacamole_user '
                    . 'SET username = ?, '
                    . 'password_hash = UNHEX(SHA2(CONCAT(?, HEX(@salt)), 256)), '
                    . 'password_salt = @salt, '
                    . 'disabled = ?, '
                    . 'expired = ?, '
                    . 'access_window_start = ?, '
                    . 'access_window_end = ?, '
                    . 'valid_from = ?, '
                    . 'valid_until = ?, '
                    . 'timezone = ? '
                    . 'WHERE user_id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('ssiisssssi',
                &$username,
                &$passwordHash,
                &$disabled,
                &$expired,
                &$accessWindowStart,
                &$accessWindowEnd,
                &$validFrom,
                &$validUntil,
                &$timezone,
                &$userId
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
        $deleted = BaseSingletonGuacamole::delete('DELETE FROM guacamole_user WHERE user_id = ?', array('i', &$id));
        return $deleted;
    }
}
