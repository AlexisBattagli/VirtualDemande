<?php

/**
 * Description of Guacamole_Connection_ParameterDAL
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once('BaseSingletonGuacamole.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_Connection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_Connection_Parameter.php');

class Guacamole_Connection_ParameterDAL {
    /*
     * Retourne l'ensemble des Guacamole_Connection_Parameter qui sont en base
     * Lister par Connection_id ASC puis parameter_name ASC puis parameter_value ASC
     * 
     * @return array[Guacamole_Connection_Parameter] Tous les Guacamole_Connection_Parameter sont placés dans un Tableau
     */

    public static function findAll()
    {
        $mesGuacamoleConnectionParameters = array();

        $data = BaseSingleton::select('SELECT guacamole_connection_parameter.connection_id as connection_id, '
                        . 'guacamole_connection_parameter.parameter_name as parameter_name, '
                        . 'guacamole_connection_parameter.parameter_value as parameter_value '
                        . ' FROM guacamole_connection_parameter'
                        . ' ORDER BY guacamole_connection_parameter.connection_id ASC, guacamole_connection_parameter.parameter_name ASC, guacamole_connection_parameter.parameter_value ASC');

        foreach ($data as $row)
        {
            $guacamoleConnectionParameter = new Guacamole_Connection_Parameter();
            $guacamoleConnectionParameter->hydrate($row);
            $mesGuacamoleConnectionParameters[] = $guacamoleConnectionParameter;
        }

        return $mesGuacamoleConnectionParameters;
    }
    
    /*
     * Retourne l'ensemble des Guacamole_Connection_Parameter pour une connection_id passée en param
     * 
     * @param int $connectionId
     * @return  array[Guacamole_Connection_Parameter]
     */

    public static function findByConnection($connectionId)
    {
        $mesGuacamoleConnectionParameters = array();

        $data = BaseSingleton::select('SELECT guacamole_connection_parameter.connection_id as connection_id, '
                        . 'guacamole_connection_parameter.parameter_name as parameter_name, '
                        . 'guacamole_connection_parameter.parameter_value as parameter_value '
                        . ' FROM guacamole_connection_parameter'
                        . ' WHERE guacamole_connection_parameter.connection_id = ?', array('i', &$connectionId));

        foreach ($data as $row)
        {
            $guacamoleConnectionParameter = new Guacamole_Connection_Parameter();
            $guacamoleConnectionParameter->hydrate($row);
            $mesGuacamoleConnectionParameters[] = $guacamoleConnectionParameter;
        }

        return $mesGuacamoleConnectionParameters;
    }
    
    /*
     * Retourne le Guacamole_Connection_Parameter correspondant au couple connectionId/parameterName
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * 
     * @param int connectionId, string parameterName
     * @return Guacamole_Connection_Parameter | null
     */

    public static function findByCP($connectionId, $parameterName)
    {
        $data = BaseSingleton::select('SELECT guacamole_connection_parameter.connection_id as connection_id, '
                        . 'guacamole_connection_parameter.parameter_name as parameter_name, '
                        . 'guacamole_connection_parameter.parameter_value as parameter_value '
                        . ' FROM guacamole_connection_parameter'
                        . ' WHERE guacamole_connection_parameter.connection_id = ? AND guacamole_connection_parameter.parameter_name = ?', array('ii', &$connectionId, &$parameterName));
        $guacamoleConnectionParameter = new Guacamole_Connection_Parameter();

        if (sizeof($data) > 0)
        {
            $guacamoleConnectionParameter->hydrate($data[0]);
        }
        else
        {
            $guacamoleConnectionParameter = null;
        }
        return $guacamoleConnectionParameter;
    }
    
    /*
     * Insère ou met à jour la Guacamole_Connection_Parameter donnée en paramètre.
     * Pour cela on vérifie si l'id de connection_id, parameter_name transmis sont uniques.
     * Si le couple return null alors il faut insèrer, sinon update aux id transmis.
     * 
     * @param Guacamole_Connection_Parameter $guacamoleConnectionParameter
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($guacamoleConnectionParameter)
    {

        //Récupère les valeurs de l'objet Guacamole_Connection_Parameter passé en para de la méthode
        $connectionId=$guacamoleConnectionParameter->getConnection()->getConnectionId(); //int
        $parameterName=$guacamoleConnectionParameter->getParameterName(); //string
        $parameterValue=$guacamoleConnectionParameter->getParameterValue(); //string

        if (is_null(findByCP($connectionId, $parameterName)))
        {
            $sql = 'INSERT INTO guacamole_connection_parameter (connection_id, parameter_name, parameter_value) '
                    . ' VALUES (?,?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('iss',
                &$connectionId,
                &$parameterName,
                &$parameterValue
            );
        }
        else
        {
            $sql = 'UPDATE guacamole_connection_parameter '
                    . 'SET connection_id = ?, '
                    . 'parameter_name = ?, '
                    . 'parameter_value = ? '
                    . 'WHERE connection_id = ? AND parameter_name = ?';

            //Prépare les info concernant les type de champs
            $params = array('issis',
                &$connectionId,
                &$parameterName,
                &$parameterValue,
                &$connectionId,
                &$parameterName
            );
        }

        //Exec la requête
        $idInsert = BaseSingletonGuacamole::insertOrEdit($sql, $params);

        return $idInsert;
    }

    /*
     * Supprime la Guacamole_Connection_Parameter correspondant au couple d'id de connectionId/parameterName donné en paramètre
     * 
     * @param int connectionId, string parameterName
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($connectionId, $parameterName)
    {
        $deleted = BaseSingletonGuacamole::delete('DELETE FROM guacamole_connection_parameter WHERE connection_id = ? AND parameter_name = ?', array('is', &$connectionId, &$parameterName));
        return $deleted;
    }
}
