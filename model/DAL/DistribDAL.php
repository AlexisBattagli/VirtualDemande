<?php
/*
 * Description of DistribDAL
 *
 * @author Alexis
 * @author Aurelie
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Distrib.php');

class DistribDAL 
{
    /*
     * Retourne la distrib correspondant à l'id donné
     * 
     * @param int $id
     * @return Distrib
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT distrib.id as id, '
                        . 'distrib.nom as nom, '
                        . 'distrib.archi as archi, '
                        . 'distrib.version as version '
                        . 'distrib.ihm as ihm '
                        . ' FROM distrib'
                        . ' WHERE distrib.id = ?', array('i', &$id));
        $distrib = new Distrib();
        if (sizeof($data) > 0)
        {
            $distrib->hydrate($data[0]);
        }
        else
        {
            $distrib = null;
        }
        return $distrib;
    }
    
    /*
     * Retourne l'ensemble des Distrib qui sont en base
     * 
     * @return array[Distrib] Toutes les Distrib sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesDistrib = array();

        $data = BaseSingleton::select('SELECT distrib.id as id, '
                        . 'distrib.nom as nom, '
                        . 'distrib.archi as archi, '
                        . 'distrib.version as version '
                        . 'distrib.ihm as ihm '
                        . ' FROM distrib'
                . ' ORDER BY distrib.nom ASC');

        foreach ($data as $row)
        {
            $distrib = new Distrib();
            $distrib->hydrate($row);
            $mesDistrib[] = $distrib;
        }

        return $mesDistrib;
    }
    
    /*
     * Insère ou met à jour la Distrib donnée en paramètre.
     * Pour cela on vérifie si l'id de la Distrib transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insérer, sinon update à l'id transmis.
     * 
     * @param Distrib distrib
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($distrib)
    {

        //Récupère les valeurs de l'objet distrib passé en para de la méthode
        $nom = $distrib->getNom(); //string
        $archi = $distrib->getArchi(); //string
        $version = $distrib->getVersion(); //string
        $ihm = $distrib->getIhm(); //string
        $id = $distrib->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO distrib (nom, archi, version, ihm) '
                    . ' VALUES (?,?,?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('isss',
                &$nom,
                &$archi,
                &$version,
                &$ihm
            );
        }
        else
        {
            $sql = 'UPDATE distrib '
                    . 'SET nom = ?, '
                    . 'archi = ?, '
                    . 'version = ?, '
                    . 'ihm = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('isssi',
                &$nom,
                &$archi,
                &$version,
                &$ihm,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime la Distrib correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM distrib WHERE id = ?', array('i', &$id));
        return $deleted;
    }
    
}