<?php

/**
 * Description of CoeurDAL
 *
 * @author Alexis
 * @author Aurelie
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Coeur.php');

class CoeurDAL {
    /*
     * Retourne le coeur correspondant à l'id donné
     * 
     * @param int $id
     * @return Coeur
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT coeur.id as id, '
                        . 'coeur.valeur as valeur, '
                        . 'coeur.visible as visible '
                        . ' FROM coeur'
                        . ' WHERE coeur.id = ?', array('i', &$id));
        $coeur = new Coeur();
        if (sizeof($data) > 0)
        {
            $coeur->hydrate($data[0]);
        }
        else
        {
            $coeur = null;
        }
        return $coeur;
    }
    
    /*
     * Retourne l'ensemble des coeur qui sont en base
     * 
     * @return array[Coeur] Tous les Coeur sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesCoeurs = array();

        $data = BaseSingleton::select('SELECT coeur.id as id, '
                        . 'coeur.valeur as valeur, '
                        . 'coeur.visible as visible '
                        . ' FROM coeur'
                . ' ORDER BY coeur.valeur ASC');

        foreach ($data as $row)
        {
            $coeur = new Coeur();
            $coeur->hydrate($row);
            $mesCoeurs[] = $coeur;
        }

        return $mesCoeurs;
    }
    
    /*
     * Insère ou met à jour le Coeur donné en paramètre.
     * Pour cela on vérifie si l'id du Coeur transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Coeur coeur
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($coeur)
    {

        //Récupère les valeurs de l'objet coeur passé en para de la méthode
        $valeur = $coeur->getValeur(); //int
        $visible = $coeur->getVisible(); //bool
        $id = $coeur->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO coeur (valeur, visible) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ib',
                &$valeur,
                &$visible
            );
        }
        else
        {
            $sql = 'UPDATE coeur '
                    . 'SET valeur = ?, '
                    . 'visible = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('ibi',
                &$valeur,
                &$visible,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime le  Coeur correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM coeur WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}
