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
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/VirtualDemande/model/class/Distrib.php');
require_once('/var/www/VirtualDemande/model/class/Distrib.php');

class DistribDAL 
{
    /*
     * Retourne la distrib par défaut
     * 
     * @return Distrib
     */
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT distrib.id as id, '
                        . 'distrib.nom as nom, '
                        . 'distrib.archi as archi, '
                        . 'distrib.version as version, '
                        . 'distrib.ihm as ihm, '
                        . 'distrib.visible as visible '
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
                        . 'distrib.version as version, '
                        . 'distrib.ihm as ihm, '
                        . 'distrib.visible as visible '
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
                        . 'distrib.version as version, '
                        . 'distrib.ihm as ihm, '
                        . 'distrib.visible as visible '
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
     * Retourne la Distrib correspondant à l'ensemble d'attributs nom/archi/version/ihm/visible
     * Cet ensemble étant unique, il n'y qu'une seule ligne retournée.
     * Il est recherché sans tenir compte de la casse sur nom/archi/version/ihm/visible
     * 
     * @param string nom, string archi, string version, string ihm
     * @return Distrib | null
     */
     public static function findByNAVI($nom, $archi, $version, $ihm, $visible)
    {
        $data = BaseSingleton::select('SELECT distrib.id as id, '
                        . 'distrib.nom as nom, '
                        . 'distrib.archi as archi, '
                        . 'distrib.version as version, '
                        . 'distrib.ihm as ihm, '
                        . 'distrib.visible as visible '
                        . ' FROM distrib'
                        . ' WHERE LOWER(distrib.nom) = LOWER(?) AND LOWER(distrib.archi) = LOWER(?) AND LOWER(distrib.version) = LOWER(?) AND LOWER(distrib.ihm) = LOWER(?) AND distrib.visible = ?', array('ssssi', &$nom, &$archi, &$version, &$ihm, &$visible));
        $distrib = new Distrib();

        if (sizeof($data) > 0)
        {
            $distrib->hydrate($data[0]);
        }
        else 
        {
            $distrib=null;
        }
         return $distrib;
    }
    
    /*
     * Retourne l'ensemble des Distrib qui sont visibles
     * 
     * @return array[Distrib] Toutes les Distrib sont placées dans un Tableau
     */
    public static function findByVisible()
    {
        $mesDistrib = array();

        $data = BaseSingleton::select('SELECT distrib.id as id, '
                        . 'distrib.nom as nom, '
                        . 'distrib.archi as archi, '
                        . 'distrib.version as version, '
                        . 'distrib.ihm as ihm, '
                        . 'distrib.visible as visible '
                        . ' FROM distrib'
                . ' WHERE distrib.visible = 0');

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
        $visible = $distrib->getVisible(); //bool
        $id = $distrib->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO distrib (nom, archi, version, ihm, visible) '
                    . ' VALUES (?,?,?,?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('ssssi',
                &$nom,
                &$archi,
                &$version,
                &$ihm,
                &$visible
            );
        }
        else
        {
            $sql = 'UPDATE distrib '
                    . 'SET nom = ?, '
                    . 'archi = ?, '
                    . 'version = ?, '
                    . 'ihm = ?, '
                    . 'visible = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('ssssii',
                &$nom,
                &$archi,
                &$version,
                &$ihm,
                &$visible,
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