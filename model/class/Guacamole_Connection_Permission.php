<?php

/**
 * Description of Guacamole_Connection_Permission
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_UserDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_Connection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');

class Guacamole_Connection_Permission {
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
     * Connexion à paramétrer
     * @var Guacamole_Connection 
     */
    private $connection;
    
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
    
    public function Guacamole_Connection_Permission(
    $user = null, $connection = null, $permission="Il n'y a pas de permission"
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
        if (is_null($connection))
        {
            $connection = Guacamole_ConnectionDAL::findByDefault();
            $this->connection = $connection;
        }
        else
        {
            $this->connection = $connection;
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
        $this->connection = $dataSet['connection_id'];
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
        else if (is_a($user, "User"))
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
        else if (is_a($this->user, "User"))
        {
            $user = $this->user;
        }
        return $user;
    }
    
    //connection
    public function setConnection($connection)
    {
        if (is_string($connection))
        {
            $connection = (int) $connection;
            $this->connection = Guacamole_ConnectionDAL::findById($connection);
        }
        else if (is_int($connection))
        {
            $this->connection = Guacamole_ConnectionDAL::findById($connection);
        }
        else if (is_a($connection, "Connection"))
        {
            $this->connection = $connection;
        }
    }

    public function getConnection()
    {
        $connection = null;
        if (is_int($this->connection))
        {
            $connection = Guacamole_ConnectionDAL::findById($this->connection);
            $this->connection = $connection;
        }
        else if (is_a($this->connection, "Connection"))
        {
            $connection = $this->connection;
        }
        return $connection;
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
