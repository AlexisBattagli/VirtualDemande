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
        $data = BaseSingletonGuacamole::select('SELECT guacamole_user.user_id as user_id, '
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
        $data = BaseSingletonGuacamole::select('SELECT guacamole_user.user_id as user_id, '
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

        $data = BaseSingletonGuacamole::select('SELECT guacamole_user.user_id as user_id, '
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
        $data = BaseSingletonGuacamole::select('SELECT guacamole_user.user_id as user_id, '
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
        $newguacamoleUser=null;

        if ($userId < 0)
        {
            
            /*
             * SET @salt = UNHEX(SHA2(UUID(), 256));
INSERT INTO guacamole_user (username, password_hash, password_salt, disabled, expired, access_window_start, access_window_end, valid_from, valid_until, timezone)
VALUES ("pass",UNHEX(SHA2(CONCAT("pass", HEX(UNHEX(SHA2(UUID(), 256)))), 256)),UNHEX(SHA2(UUID(), 256)),0,0,NULL,NULL,NULL,NULL,NULL)
             * //Le problème est que l'exécution doit se faire l'un après l'autre car sinon si on les fait séparer il garde pas en mémoire @salt !!!
             
            sql = ' INSERT INTO guacamole_user (username, password_hash, password_salt, disabled, expired, access_window_start, access_window_end, valid_from, valid_until, timezone) '
                  . ' VALUES (?,UNHEX(SHA2(CONCAT(?, HEX(UNHEX(SHA2(UUID(), 256)))), 256)),UNHEX(SHA2(UUID(), 256)),?,?,?,?,?,?,?) ';
            //Ne s'insère pas car password_salt n'est pas le bon... : la bonne requête serait : SET @salt = UNHEX(SHA2(UUID(), 256));
//INSERT INTO guacamole_user (username, password_hash, password_salt, disabled, expired, access_window_start, access_window_end, valid_from, valid_until, timezone)
//VALUES ("pass",@UNHEX(SHA2(CONCAT("pass", HEX(UNHEX(SHA2(UUID(), 256)))), 256)),@salt,0,0,NULL,NULL,NULL,NULL,NULL)
             
            /*$sql ='INSERT INTO guacamole_user (username, password_hash, password_salt, disabled, expired, access_window_start, access_window_end, valid_from, valid_until, timezone)'
            .'VALUES (?, x’sha256(?)’, 256), null, 0, 0, ?, ?, ?, ?, ?)';
            */
            
            $sql = ' INSERT INTO guacamole_user (username, password_hash, password_salt, disabled, expired, access_window_start, access_window_end, valid_from, valid_until, timezone) '
                  . ' VALUES (?,UNHEX(SHA2(CONCAT(?, HEX(UNHEX(SHA2(UUID(), 256)))), 256)),UNHEX(SHA2(UUID(), 256)),?,?,?,?,?,?,?) ';
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
            
            $idInsert = BaseSingletonGuacamole::insertOrEdit($sql, $params);
            $newguacamoleUser=new Guacamole_User();
            $newguacamoleUser=Guacamole_UserDAL::findById($idInsert);
            if ($newguacamoleUser != null)
            {
                //echo "pas là";
                $newguacamoleUser->setPasswordHash($passwordHash);
                $idInsert = Guacamole_UserDAL::insertOnDuplicate($newguacamoleUser);echo "OK";
            }
        }
        else
        { echo "Ici";
        
        /*Bonne requete :
         * UPDATE guacamole_user
                    SET username = "aa",
                    password_hash = UNHEX(SHA2(CONCAT("aa", HEX(password_salt)), 256)),
                    disabled = 0, 
                    expired = 0, 
                    access_window_start = NULL,
                    access_window_end = NULL, 
                    valid_from = NULL, 
                    valid_until = NULL, 
                    timezone = NULL 
                    WHERE user_id = 76;
        
              
            $sql = ' UPDATE guacamole_user '
                    . 'SET username = ?, '
                    . 'password_hash = UNHEX(SHA2(CONCAT(?, HEX(guacamole_user.password_salt)), 256)), '
                    . 'disabled = ?, '
                    . 'expired = ?, '
                    . 'access_window_start = ?, '
                    . 'access_window_end = ?, '
                    . 'valid_from = ?, '
                    . 'valid_until = ?, '
                    . 'timezone = ? '
                    . 'WHERE user_id = ? ';
*/
        $sql ='UPDATE guacamole_user '
                    .'SET username = ?, '
                    .'password_hash = UNHEX(SHA2(CONCAT(?, HEX(password_salt)), 256)), '
                    .'disabled = ?, '
                    .'expired = ?, '
                    .'access_window_start = ?, '
                    .'access_window_end = ?, '
                    .'valid_from = ?, '
                    .'valid_until = ?, '
                    .'timezone = ? '
                    .'WHERE user_id = ?;';
        
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
            echo $userId;
            
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
