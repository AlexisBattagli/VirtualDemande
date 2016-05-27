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
}
