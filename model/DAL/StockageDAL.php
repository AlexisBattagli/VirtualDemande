<?php

/**
 * Description of StockageDAL
 *
 * @author Alexis
 * @author Aurelie
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Stockage.php');

class StockageDAL {
    /*
     * Retourne le stockage par défaut
     * 
     * @return Stockage
     */
    
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT stockage.id as id, '
                        . 'stockage.valeur as valeur, '
                        . 'stockage.visible as visible '
                        . ' FROM stockage'
                        . ' WHERE stockage.id = ?', array('i', &$id));
        $stockage = new Stockage();
        if (sizeof($data) > 0)
        {
            $stockage->hydrate($data[0]);
        }
        else
        {
            $stockage = null;
        }
        return $stockage;
    }
    
    /*
     * Retourne le stockage correspondant à l'id donné
     * 
     * @param int $id
     * @return Stockage
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT stockage.id as id, '
                        . 'stockage.valeur as valeur, '
                        . 'stockage.visible as visible '
                        . ' FROM stockage'
                        . ' WHERE stockage.id = ?', array('i', &$id));
        $stockage = new Stockage();
        if (sizeof($data) > 0)
        {
            $stockage->hydrate($data[0]);
        }
        else
        {
            $stockage = null;
        }
        return $stockage;
    }
    
    /*
     * Retourne l'ensemble des stockage qui sont en base
     * 
     * @return array[Stockage] Tous les Stockage sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesStockages = array();

        $data = BaseSingleton::select('SELECT stockage.id as id, '
                        . 'stockage.valeur as valeur, '
                        . 'stockage.visible as visible '
                        . ' FROM stockage'
                . ' ORDER BY stockage.valeur ASC');

        foreach ($data as $row)
        {
            $stockage = new Stockage();
            $stockage->hydrate($row);
            $mesStockages[] = $stockage;
        }

        return $mesStockages;
    }
    
    /*
     * Retourne le stockage correspondant au couple valeur
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur valeur
     * 
     * @param int valeur
     * @return Stockage | null
     */
    
    public static function findByValeur($valeur)
    {
        $data = BaseSingleton::select('SELECT stockage.id as id, '
                        . 'stockage.valeur as valeur, '
                        . 'stockage.visible as visible '
                        . ' FROM stockage'
                        . ' WHERE LOWER(stockage.valeur) = LOWER(?)', array('i', &$valeur));
        $stockages = new Stockage();

        if (sizeof($data) > 0)
        {
            $stockages->hydrate($data[0]);
        }
        else
        {
            $stockages=null;
        }
        return $stockages;
    }
    
    /*
     * Insère ou met à jour le Stockage donné en paramètre.
     * Pour cela on vérifie si l'id du Stockage transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Stockage stockage
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($stockage)
    {

        //Récupère les valeurs de l'objet stockage passé en para de la méthode
        $valeur = $stockage->getValeur(); //int
        $visible = $stockage->getVisible(); //bool
        $id = $stockage->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO stockage (valeur, visible) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ib',
                &$valeur,
                &$visible
            );
        }
        else
        {
            $sql = 'UPDATE stockage '
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
     * Supprime le  Stockage correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM stockage WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}
