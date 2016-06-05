<?php

/**
 * Description of Guacamole_User_Permission
 *
 * @author Alexis
 * @author Aurelie
 */

//import
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_User.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_UserDAL.php');
require_once('/var/www/VirtualDemande/model/class/Guacamole_User.php');
require_once('/var/www/VirtualDemande/model/DAL/Guacamole_UserDAL.php');

class Guacamole_User_Permission {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    
    /*
     * Utilisateur à paramétrer
     * @var Guacamole_User
     */
    private $user;
    
    /*
     * Utilisateur impacté par l'utilisateur
     * @var Guacamole_User
     */
    private $affectedUser;
    
    /*
     * Permissions de l'utilisateur
     * @var string
     */
    private $permission;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */
    
    public function Guacamole_User_Permission(
    $user = null, $affectedUser = null, $permission="Il n'y a pas de permission"
    )
    {
       if (is_null($user))
        {
            $user = Guacamole_UserDAL::findByDefault();
            $this->user = $user;
        }
        else
        {
            $this->user = $user;
        } 
        if (is_null($affectedUser))
        {
            $affectedUser = Guacamole_UserDAL::findByDefault();
            $this->affectedUser = $affectedUser;
        }
        else
        {
            $this->affectedUser = $affectedUser;
        } 
        $this->permission = $permission;
    }
    
    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->user = $dataSet['user_id'];
        $this->affectedUser = $dataSet['affected_user_id'];
        $this->permission = $dataSet['permission'];
    }
    
    /*
      ==============================
      ======= GETTER/SETTER ========
      ==============================
     */
    
    //user
    public function setUser($user)
    {
        if (is_string($user))
        {
            $user = (int) $user;
            $this->user = Guacamole_UserDAL::findById($user);
        }
        else if (is_int($user))
        {
            $this->user = Guacamole_UserDAL::findById($user);
        }
        else if (is_a($user, "Guacamole_User"))
        {
            $this->user = $user;
        }
    }

    public function getUser()
    {
        $user = null;
        if (is_int($this->user))
        {
            $user = Guacamole_UserDAL::findById($this->user);
            $this->user = $user;
        }
        else if (is_a($this->user, "Guacamole_User"))
        {
            $user = $this->user;
        }
        return $user;
    }
    
    //affectedUser
    public function setAffectedUser($affectedUser)
    {
        if (is_string($affectedUser))
        {
            $affectedUser = (int) $affectedUser;
            $this->affectedUser = Guacamole_UserDAL::findById($affectedUser);
        }
        else if (is_int($affectedUser))
        {
            $this->affectedUser = Guacamole_UserDAL::findById($affectedUser);
        }
        else if (is_a($affectedUser, "Guacamole_User"))
        {
            $this->affectedUser = $affectedUser;
        }
    }

    public function getAffectedUser()
    {
        $affectedUser = null;
        if (is_int($this->affectedUser))
        {
            $affectedUser = Guacamole_UserDAL::findById($this->affectedUser);
            $this->affectedUser = $affectedUser;
        }
        else if (is_a($this->affectedUser, "Guacamole_User"))
        {
            $affectedUser = $this->affectedUser;
        }
        return $affectedUser;
    }
    
    //permission
    public function setPermission($permission)
    {
        if (is_string($permission))
        {
            $this->permission = $permission;
        }
    }

    public function getPermission()
    {
        return $this->permission;
    }
}
