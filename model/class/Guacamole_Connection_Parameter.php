<?php

/**
 * Description of Guacamole_Connection_Parameter
 *
 * @author Alexis
 * @author Aurelie
 */

//import
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');
require_once('/var/www/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');

class Guacamole_Connection_Parameter {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    
    /*
     * Connexion à paramétrer
     * @var Guacamole_Connection 
     */
    private $connection;
    
    /*
     * Nom du parametre de la connexion
     * @var string
     */
    private $parameterName;
    
    /*
     * Valeur du paramètre de la connexion
     * @var string
     */
    private $parameterValue;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */
    
    public function Guacamole_Connection_Parameter(
    $connection = null, $parameterName ="Il n'y a pas de nom de paramètre de la connexion", $parameterValue ="Il n'y a pas de valeur pour le paramètre de la connexion"
    )
    {
       if (is_null($connection))
        {
            $connection = Guacamole_ConnectionDAL::findByDefault();
            $this->connection = $connection;
        }
        else
        {
            $this->connection = $connection;
        } 
        $this->parameterName = $parameterName;
        $this->parameterValue = $parameterValue;
    }
    
    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->connection = $dataSet['connection_id'];
        $this->parameterName = $dataSet['parameter_name'];
        $this->parameterValue = $dataSet['parameter_value'];
    }
    
    /*
      ==============================
      ======= GETTER/SETTER ========
      ==============================
     */
    
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
        else if (is_a($connection, "Guacamole_Connection"))
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
        else if (is_a($this->connection, "Guacamole_Connection"))
        {
            $connection = $this->connection;
        }
        return $connection;
    }

    //parameterName
    public function setParameterName($parameterName)
    {
        if (is_string($parameterName))
        {
            $this->parameterName = $parameterName;
        }
    }

    public function getParameterName()
    {
        return $this->parameterName;
    }
    
    //parameterValue
    public function setParameterValue($parameterValue)
    {
        if (is_string($parameterValue))
        {
            $this->parameterValue = $parameterValue;
        }
    }

    public function getParameterValue()
    {
        return $this->parameterValue;
    }
}
