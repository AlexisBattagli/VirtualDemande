<?php

/**
 * Description of Guacamole_Connection_Parameter
 *
 * @author Alexis
 * @author Aurelie
 */
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
}
