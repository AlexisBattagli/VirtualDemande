<?php

/**
 * Description of RoleDAL
 *
 * @author Alexis
 * @author Aurelie 
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Role.php');

class RoleDAL 
{
    /*
     * Retourne le role correspondant à l'id donné
     * 
     * @param int $id
     * @return Role
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT role.id as id, '
                        . 'role.role as role, '
                        . 'role.description as description '
                        . ' FROM role'
                        . ' WHERE role.id = ?', array('i', &$id));
        $role = new Role();
        if (sizeof($data) > 0)
        {
            $role->hydrate($data[0]);
        }
        else
        {
            $role = null;
        }
        return $role;
    }

    /*
     * Retourne l'ensemble des roles qui sont en base
     * 
     * @return array[Role] Tous les Roles sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesRoles = array();

        $data = BaseSingleton::select('SELECT role.id as id, '
                        . 'role.role as role, '
                        . 'role.description as description '
                        . ' FROM role'
                . ' ORDER BY role.role ASC');

        foreach ($data as $row)
        {
            $role = new Role();
            $role->hydrate($row);
            $mesRoles[] = $role;
        }

        return $mesRoles;
    }
    
    //TO DO findByRole
    
    /*
     * Insère ou met à jour le Role donné en paramètre.
     * Pour cela on vérifie si l'id du Role transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Role role
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($role)
    {

        //Récupère les valeurs de l'objet role passé en para de la méthode
        $role_nom = $role->getRole(); //string
        $description = $role->getDescription(); //string
        $id = $role->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO role (role, description) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ss',
                &$role_nom,
                &$description
            );
        }
        else
        {
            $sql = 'UPDATE role '
                    . 'SET role = ?, '
                    . 'description = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('ssi',
                &$role_nom,
                &$description,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime le  Role correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM role WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}