<?php

/**
 * Description of Guacamole_Connection
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');

class Guacamole_Connection 
{
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    
    /*
     * Id de la connexion
     * @var int 
     */
    
    private $connectionId;
    
    /*
     * Nom de la connexion
     * @var string
     */
    private $connectionName;
    
    /*
     * Connexion parent de la connexion
     * @var Guacamole_Connection
     */
    private $parent;
    
    /*
     * Protocole de la connexion
     * @var string
     */
    private $protocol;
    
    /*
     * Nombre de la connexion maximum
     * @var int 
     */
    
    private $maxConnections;
    
    /*
     * Nombre de la connexion maximum par utilisateur
     * @var int 
     */
    
    private $maxConnectionsPerUser;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */
    
    public function Guacamole_Connection(
    $connectionId = -1, $connectionName="Il n'y a pas de nom à cette connexion", $parent = null, $protocol="Il n'y a pas de protocol pour cette connexion", $maxConnections=null, $maxConnectionsPerUser=null
    )
    {
        $this->connectionId = $connectionId;
        $this->connectionName = $connectionName;
        $this->parent = $parent;
        $this->protocol = $protocol;
        $this->maxConnections = $maxConnections;
        $this->maxConnectionsPerUser = $maxConnectionsPerUser;
    }
    
    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->connectionId = $dataSet['connection_id'];
        $this->connectionName = $dataSet['connection_name'];
        $this->parent = $dataSet['parent_id'];
        $this->protocol = $dataSet['protocol'];
        $this->maxConnections = $dataSet['max_connections'];
        $this->maxConnectionsPerUser = $dataSet['max_connections_per_user'];
    }
    
    /*
      ==============================
      ======= GETTER/SETTER ========
      ==============================
     */

    //connectionId
    public function setConnectionId($connectionId)
    {
        if (is_int($connectionId))
        {
            $this->connectionId = $connectionId;
        }
    }

    public function getConnectionId()
    {
        return $this->connectionId;
    }
    
    //connectionName
    public function setConnectionName($connectionName)
    {
        if (is_string($connectionName))
        {
            $this->connectionName = $connectionName;
        }
    }

    public function getConnectionName()
    {
        return $this->connectionName;
    }
    
    //parent
    public function setParent($parent)
    {
        if (is_string($parent))
        {
            $parent = (int) $parent;
            $this->parent = Guacamole_ConnectionDAL::findById($parent);
        }
        else if (is_int($parent))
        {
            $this->parent = Guacamole_ConnectionDAL::findById($parent);
        }
        else if (is_a($parent, "Guacamole_User"))
        {
            $this->parent = $parent;
        }
    }

    public function getParent()
    {
        $parent = null;
        if (is_int($this->parent))
        {
            $parent = Guacamole_ConnectionDAL::findById($this->parent);
            $this->parent = $parent;
        }
        else if (is_a($this->parent, "Guacamole_User"))
        {
            $parent = $this->parent;
        }
        return $parent;
    }
    
    //protocol
    public function setProtocol($protocol)
    {
        if (is_string($protocol))
        {
            $this->protocol = $protocol;
        }
    }

    public function getProtocol()
    {
        return $this->protocol;
    }
    
    //maxConnections
    public function setMaxConnections($maxConnections)
    {
        if (is_int($maxConnections))
        {
            $this->maxConnections = $maxConnections;
        }
    }

    public function getMaxConnections()
    {
        return $this->maxConnections;
    }
    
    //maxConnectionsPerUser
    public function setMaxConnectionsPerUser($maxConnectionsPerUser)
    {
        if (is_int($maxConnectionsPerUser))
        {
            $this->maxConnectionsPerUser = $maxConnectionsPerUser;
        }
    }

    public function getMaxConnectionsPerUser()
    {
        return $this->maxConnectionsPerUser;
    }
}
