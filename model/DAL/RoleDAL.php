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
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Role.php');
require_once('/var/www/VirtualDemande/model/class/Role.php');

class RoleDAL 
{
    /*
     * Retourne le role par défaut
     * 
     * @return Role
     */
    
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT role.id as id, '
                        . 'role.nom_role as nom_role, '
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
     * Retourne le role correspondant à l'id donné
     * 
     * @param int $id
     * @return Role
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT role.id as id, '
                        . 'role.nom_role as nom_role, '
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
                        . 'role.nom_role as nom_role, '
                        . 'role.description as description '
                        . ' FROM role'
                . ' ORDER BY role.nom_role ASC');

        foreach ($data as $row)
        {
            $role = new Role();
            $role->hydrate($row);
            $mesRoles[] = $role;
        }

        return $mesRoles;
    }
    
    /*
     * Retourne le role correspondant au role
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur role
     * 
     * @param string role
     * @return Role | null
     */
    
    public static function findByRole($role)
    {
        $data = BaseSingleton::select('SELECT role.id as id, '
                        . 'role.nom_role as nom_role, '
                        . 'role.description as description '
                        . ' FROM role'
                        . ' WHERE LOWER(role.nom_role) = LOWER(?)', array('s', &$role));
        $roles = new Role();

        if (sizeof($data) > 0)
        {
            $roles->hydrate($data[0]);
        }
        else
        {
            $roles=null;
        }
        return $roles;
    }
    
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
        $nomRole = $role->getNomRole(); //string
        $description = $role->getDescription(); //string
        $id = $role->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO role (nom_role, description) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ss',
                &$nomRole,
                &$description
            );
        }
        else
        {
            $sql = 'UPDATE role '
                    . 'SET nom_role = ?, '
                    . 'description = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('ssi',
                &$nomRole,
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