<?php

/**
 * Description of CpuDAL
 *
 * @author Alexis
 * @author Aurelie
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Cpu.php');

class CpuDAL {
    /*
     * Retourne le cpu par défaut
     * 
     * @return Cpu
     */
    
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT cpu.id as id, '
                        . 'cpu.nb_coeur as nb_coeur, '
                        . 'cpu.visible as visible '
                        . ' FROM cpu'
                        . ' WHERE cpu.id = ?', array('i', &$id));
        $cpu = new Cpu();
        if (sizeof($data) > 0)
        {
            $cpu->hydrate($data[0]);
        }
        else
        {
            $cpu = null;
        }
        return $cpu;
    }
    
    /*
     * Retourne le cpu correspondant à l'id donné
     * 
     * @param int $id
     * @return Cpu
     */
    
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT cpu.id as id, '
                        . 'cpu.nb_coeur as nb_coeur, '
                        . 'cpu.visible as visible '
                        . ' FROM cpu'
                        . ' WHERE cpu.id = ?', array('i', &$id));
        $cpu = new Cpu();
        if (sizeof($data) > 0)
        {
            $cpu->hydrate($data[0]);
        }
        else
        {
            $cpu = null;
        }
        return $cpu;
    }
    
    /*
     * Retourne l'ensemble des cpu qui sont en base
     * 
     * @return array[Cpu] Tous les Cpu sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesCpus = array();

        $data = BaseSingleton::select('SELECT cpu.id as id, '
                        . 'cpu.nb_coeur as nb_coeur, '
                        . 'cpu.visible as visible '
                        . ' FROM cpu'
                . ' ORDER BY cpu.nb_coeur ASC');

        foreach ($data as $row)
        {
            $cpu = new Cpu();
            $cpu->hydrate($row);
            $mesCpus[] = $cpu;
        }

        return $mesCpus;
    }
    
    /*
     * Retourne l'ensemble des cpu qui sont visibles
     * 
     * @return array[Cpu] Tous les Cpu sont placées dans un Tableau
     */
    public static function findByVisible()
    {
        $mesCpus = array();

        $data = BaseSingleton::select('SELECT cpu.id as id, '
                        . 'cpu.nb_coeur as nb_coeur, '
                        . 'cpu.visible as visible '
                        . ' FROM cpu'
                . ' WHERE cpu.visible = 0');

        foreach ($data as $row)
        {
            $cpu = new Cpu();
            $cpu->hydrate($row);
            $mesCpus[] = $cpu;
        }

        return $mesCpus;
    }
    
    /*
     * Retourne le cpu correspondant au cpu avec un nb de coeur voulu
     * Cette valeur étant unique, il n'y qu'une seul ligne retourner.
     * 
     * @param int nbCoeur
     * @return Cpu | null
     */
    
    public static function findByNbCoeur($nbCoeur)
    {
        $data = BaseSingleton::select('SELECT cpu.id as id, '
                        . 'cpu.nb_coeur as nb_coeur, '
                        . 'cpu.visible as visible '
                        . ' FROM cpu'
                        . ' WHERE LOWER(cpu.nb_coeur) = LOWER(?)', array('i', &$nbCoeur));
        
        $cpus = new Cpu();

        if (sizeof($data) > 0)
        {
            $cpus->hydrate($data[0]);
        }
        else
        {
            $cpus=null;
        }
        return $cpus;
    }
    
    /*
     * Insère ou met à jour le Cpu donné en paramètre.
     * Pour cela on vérifie si l'id du Cpu transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Cpu cpu
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($cpu)
    {

        //Récupère les valeurs de l'objet cpu passé en para de la méthode
        $nbCoeur = $cpu->getNbCoeur(); //int
        $visible = $cpu->getVisible(); //bool
        $id = $cpu->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO cpu (nb_coeur, visible) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('ib',
                &$nbCoeur,
                &$visible
            );
        }
        else
        {
            $sql = 'UPDATE cpu '
                    . 'SET nb_coeur = ?, '
                    . 'visible = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('ibi',
                &$nbCoeur,
                &$visible,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime le  Cpu correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM cpu WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}
