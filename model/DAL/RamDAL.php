<?php

/**
 * Description of RamDAL
 *
 * @author Alexis
 * @author Aurelie
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Ram.php');

class RamDAL {
    /*
     * Retourne le ram par défaut
     * 
     * @return Ram
     */
    
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT ram.id as id, '
                        . 'ram.valeur as valeur, '
                        . 'ram.visible as visible '
                        . ' FROM ram'
                        . ' WHERE ram.id = ?', array('i', &$id));
        $ram = new Ram();
        if (sizeof($data) > 0)
        {
            $ram->hydrate($data[0]);
        }
        else
        {
            $ram = null;
        }
        return $ram;
    }
    
    /*
     * Retourne le ram correspondant à l'id donné
     * 
     * @param int $id
     * @return Ram
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT ram.id as id, '
                        . 'ram.valeur as valeur, '
                        . 'ram.visible as visible '
                        . ' FROM ram'
                        . ' WHERE ram.id = ?', array('i', &$id));
        $ram = new Ram();
        if (sizeof($data) > 0)
        {
            $ram->hydrate($data[0]);
        }
        else
        {
            $ram = null;
        }
        return $ram;
    }
    
    /*
     * Retourne l'ensemble des ram qui sont en base
     * 
     * @return array[Ram] Tous les Ram sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesRams = array();

        $data = BaseSingleton::select('SELECT ram.id as id, '
                        . 'ram.valeur as valeur, '
                        . 'ram.visible as visible '
                        . ' FROM ram'
                . ' ORDER BY ram.valeur ASC');

        foreach ($data as $row)
        {
            $ram = new Ram();
            $ram->hydrate($row);
            $mesRams[] = $ram;
        }

        return $mesRams;
    }
    
    /*
     * Retourne l'ensemble des ram qui sont visibles
     * 
     * @return array[Ram] Tous les Ram sont placées dans un Tableau
     */
    public static function findByVisible()
    {
        $mesRams = array();

        $data = BaseSingleton::select('SELECT ram.id as id, '
                        . 'ram.valeur as valeur, '
                        . 'ram.visible as visible '
                        . ' FROM ram'
                . ' WHERE ram.visible = 1');

        foreach ($data as $row)
        {
            $ram = new Ram();
            $ram->hydrate($row);
            $mesRams[] = $ram;
        }

        return $mesRams;
    }
    
    /*
     * Retourne le ram correspondant au couple valeur
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur valeur
     * 
     * @param int valeur
     * @return Ram | null
     */
    
    public static function findByValeur($valeur)
    {
        $data = BaseSingleton::select('SELECT ram.id as id, '
                        . 'ram.valeur as valeur, '
                        . 'ram.visible as visible '
                        . ' FROM ram'
                        . ' WHERE LOWER(ram.valeur) = LOWER(?)', array('i', &$valeur));
        $rams = new Ram();

        if (sizeof($data) > 0)
        {
            $rams->hydrate($data[0]);
        }
        else
        {
            $rams=null;
        }
        return $rams;
    }
    
    /*
     * Insère ou met à jour le Ram donné en paramètre.
     * Pour cela on vérifie si l'id du Ram transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Ram ram
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($ram)
    {

        //Récupère les valeurs de l'objet ram passé en para de la méthode
        $valeur = $ram->getValeur(); //int
        $visible = $ram->getVisible(); //bool
        $id = $ram->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO ram (valeur, visible) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ii',
                &$valeur,
                &$visible
            );
        }
        else
        {
            $sql = 'UPDATE ram '
                    . 'SET valeur = ?, '
                    . 'visible = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('iii',
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
     * Supprime le  Ram correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM ram WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}
