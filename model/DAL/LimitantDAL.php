<?php

/**
 * Description of LimitantDAL
 *
 * @author Alexis
 * @author Aurelie 
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Limitant.php');

class LimitantDAL {
    /*
     * Retourne le limitant correspondant à l'id donné
     * 
     * @param int $id
     * @return Limitant
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT limitant.id as id, '
                        . 'limitant.nb_user_max as nb_user_max, '
                        . 'limitant.nb_vm_user as nb_vm_user '
                        . ' FROM limitant'
                        . ' WHERE limitant.id = ?', array('i', &$id));
        $limitant = new Limitant();
        if (sizeof($data) > 0)
        {
            $limitant->hydrate($data[0]);
        }
        else
        {
            $limitant = null;
        }
        return $limitant;
    }
    
    /*
     * Retourne l'ensemble des limitants qui sont en base
     * 
     * @return array[Limitant] Tous les Limitants sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesLimitants = array();

        $data = BaseSingleton::select('SELECT limitant.id as id, '
                        . 'limitant.nb_user_max as nb_user_max, '
                        . 'limitant.nb_vm_user as nb_vm_user '
                        . ' FROM limitant'
                . ' ORDER BY limitant.id ASC');

        foreach ($data as $row)
        {
            $limitant = new Limitant();
            $limitant->hydrate($row);
            $mesLimitants[] = $limitant;
        }

        return $mesLimitants;
    }
    
    /*
     * Retourne le limitant correspondant au couple nbUserMax/nbVMUser
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur login
     * 
     * @param int nbUserMax/nbVMUser
     * @return Limitant | null
     */
    
    public static function findByLimitant($nbUserMax, $nbVMUser)
    {
        $data = BaseSingleton::select('SELECT limitant.id as id, '
                        . 'limitant.nb_user_max as nb_user_max, '
                        . 'limitant.nb_vm_user as nb_vm_user '
                        . ' FROM limitant'
                        . ' WHERE limitant.nb_user_max = ? AND limitant.nb_vm_user = ?', array('ii', &$nbUserMax, &$nbVMUser));
        $limitants = new Limitant();

        if (sizeof($data) > 0)
        {
            $limitants->hydrate($data[0]);
        }
        else
        {
            $limitants=null;
        }
        return $limitants;
    }
    
    /*
     * Insère ou met à jour le Limitant donné en paramètre.
     * Pour cela on vérifie si l'id du Limitant transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Limitant limitant
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */
    
    public static function insertOnDuplicate($limitant)
    {

        //Récupère les valeurs de l'objet limitant passé en para de la méthode
        $nbUserMax = $limitant->getNbUserMax(); //string
        $nbVMUser = $limitant->getNbVMUser(); //string
        $id = $limitant->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO limitant (nb_user_max, nb_vm_user) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ii',
                &$nbUserMax,
                &$nbVMUser
            );
        }
        else
        {
            $sql = 'UPDATE limitant '
                    . 'SET nb_user_max = ?, '
                    . 'nb_vm_user = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('iii',
                &$nbUserMax,
                &$nbVMUser,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime le  Limitant correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM limitant WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}
