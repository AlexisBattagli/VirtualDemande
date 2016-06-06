<?php

/**
 * Description of Table_logDAL
 *
 * @author Alexis
 * @author Aurelie
 */
/*
 * IMPORT
 */
require_once('BaseSingleton.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Table_log.php');
require_once('/var/www/VirtualDemande/model/class/Table_log.php');

class Table_logDAL {
        /*
     * Retourne la ligne de Table_log par défaut
     * 
     * @return Table_log
     */
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT table_log.table_log_id as id, '
                        . 'table_log.machine as machine, '
                        . 'table_log.utilisateur as utilisateur, '
                        . 'table_log.date_heure as date_heure, '
                        . 'table_log.action as action, '
                        . 'table_log.code_retour as code_retour '
                        . ' FROM table_log'
                        . ' WHERE table_log.table_log_id = ?', array('i', &$id));
        $tableLog = new Table_log();
        if (sizeof($data) > 0)
        {
            $tableLog->hydrate($data[0]);
        }
        else
        {
            $tableLog = null;
        }
        return $tableLog;
    }

    /*
     * Retourne la ligne de Table_log correspondant à l'id donnée
     * 
     * @param int $id
     * @return Table_log
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT table_log.table_log_id as id, '
                        . 'table_log.machine as machine, '
                        . 'table_log.utilisateur as utilisateur, '
                        . 'table_log.date_heure as date_heure, '
                        . 'table_log.action as action, '
                        . 'table_log.code_retour as code_retour '
                        . ' FROM table_log'
                        . ' WHERE table_log.table_log_id = ?', array('i', &$id));
        $tableLog = new Table_log();
        if (sizeof($data) > 0)
        {
            $tableLog->hydrate($data[0]);
        }
        else
        {
            $tableLog = null;
        }
        return $tableLog;
    }

    /*
     * Retourne l'ensemble des Table_log qui sont en base
     * 
     * @return array[Table_log] Toutes les Table_log sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mestableLogs = array();

        $data = BaseSingleton::select('SELECT table_log.table_log_id as id, '
                        . 'table_log.machine as machine, '
                        . 'table_log.utilisateur as utilisateur, '
                        . 'table_log.date_heure as date_heure, '
                        . 'table_log.action as action, '
                        . 'table_log.code_retour as code_retour '
                        . ' FROM table_log'
                . ' ORDER BY table_log.machine ASC, table_log.utilisateur ASC, table_log.action ASC');

        foreach ($data as $row)
        {
            $tableLog = new Table_log();
            $tableLog->hydrate($row);
            $mestableLogs[] = $tableLog;
        }

        return $mestableLogs;
    }
    
    /*
     * Retourne la Table_log correspondant au couple machine/utilisateur/date_heure/action/code_retour
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur nom_complet
     * 
     * @param int machine, int utilisateur, string date_heure, string action, string code_retour
     * @return Table_log | null
     */
    public static function findByMUDAC($machine, $utilisateur, $dateHeure, $action, $codeRetour)
    {
        $data = BaseSingleton::select('SELECT table_log.table_log_id as id, '
                        . 'table_log.machine as machine, '
                        . 'table_log.utilisateur as utilisateur, '
                        . 'table_log.date_heure as date_heure, '
                        . 'table_log.action as action, '
                        . 'table_log.code_retour as code_retour '
                        . ' FROM table_log'
                        . ' WHERE table_log.machine = ? AND table_log.utilisateur = ? AND table_log.date_heure = ? AND LOWER(table_log.action) = LOWER(?) AND LOWER(table_log.code_retour) = LOWER(?)', array('sssss', &$machine, &$utilisateur, &$dateHeure, &$action, &$codeRetour));
        $tableLog = new Table_log();

        if (sizeof($data) > 0)
        { echo "ok";
            $tableLog->hydrate($data[0]);
        }
        else 
        {
            $tableLog=null;
        }
         return $tableLog;
    }
    
    /*
     * Insère ou met à jour la Table_log donnée en paramètre.
     * Pour cela on vérifie si l'id de la Table_log transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Table_log $tableLog
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($tableLog)
    {

        //Récupère les valeurs de l'objet table_log passé en para de la méthode
        $machine = $tableLog->getMachine(); //string
        $utilisateur = $tableLog->getUtilisateur(); //string
        $dateHeure = $tableLog->getDateHeure(); //string
        $action = $tableLog->getAction(); //string
        $codeRetour = $tableLog->getCodeRetour(); //string
        $id = $tableLog->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO table_log (utilisateur, machine, date_heure, action, code_retour) '
                    . ' VALUES (?,?,?,?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('sssss',
                &$machine,
                &$utilisateur,
                &$dateHeure,
                &$action,
                &$codeRetour
            );
        }
        else
        {
            $sql = 'UPDATE table_log '
                    . 'SET machine = ?, '
                    . 'utilisateur = ?, '
                    . 'date_heure = ?, '
                    . 'action = ?, '
                    . 'code_retour = ? '
                    . 'WHERE table_log_id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('sssssi',
                &$machine,
                &$utilisateur,
                &$dateHeure,
                &$action,
                &$codeRetour,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime la Table_log correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM table_log WHERE table_log_id = ?', array('i', &$id));
        return $deleted;
    }

}

