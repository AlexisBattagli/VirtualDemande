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
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Table_log.php');

class Table_logDAL {
    
    /*
     * Retourne la ligne de Table_log correspondant à l'id donnée
     * 
     * @param int $id
     * @return Table_log
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT table_log.id as id, '
                        . 'table_log.Machine_id as Machine_id, '
                        . 'table_log.Utilisateur as Utilisateur_id, '
                        . 'table_log.date_heure as date_heure, '
                        . 'table_log.action as action, '
                        . 'table_log.code_retour as code_retour '
                        . ' FROM table_log'
                        . ' WHERE table_log.id = ?', array('i', &$id));
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

        $data = BaseSingleton::select('SELECT table_log.id as id, '
                        . 'table_log.Machine_id as Machine_id, '
                        . 'table_log.Utilisateur as Utilisateur_id, '
                        . 'table_log.date_heure as date_heure, '
                        . 'table_log.action as action, '
                        . 'table_log.code_retour as code_retour '
                        . ' FROM table_log'
                . ' ORDER BY table_log.Machine_id ASC, table_log.Utilisateur_id ASC, table_log.action ASC');

        foreach ($data as $row)
        {
            $tableLog = new Table_log();
            $tableLog->hydrate($row);
            $mestableLogs[] = $tableLog;
        }

        return $mestableLogs;
    }
    
    /*
     * Retourne la Table_log correspondant au couple Machine_id/Utilisateur_id/date_heure/action/code_retour
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur nom_complet
     * 
     * @param int machineID, int utilisateurID, string date_heure, string action, string code_retour
     * @return Table_log | null
     */
    public static function findByDN($machineID, $utilisateurID, $date_heure, $action, $code_retour)
    {
        $data = BaseSingleton::select('SELECT table_log.id as id, '
                        . 'table_log.Machine_id as Machine_id, '
                        . 'table_log.Utilisateur as Utilisateur_id, '
                        . 'table_log.date_heure as date_heure, '
                        . 'table_log.action as action, '
                        . 'table_log.code_retour as code_retour '
                        . ' FROM table_log'
                        . ' WHERE table_log.Machine_id = ? AND table_log.Utilisateur_id = ? AND LOWER(table_log.date_heure) = LOWER(?)AND LOWER(table_log.action) = LOWER(?) AND LOWER(table_log.code_retour) = LOWER(?)', array('iisss', &$machineID, &$utilisateurID, &$date_heure, &$action, &$code_retour));
        $tableLog = new Table_log();

        if (sizeof($data) > 0)
        {
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
        $machineId = $tableLog->getMachine()->getId(); //int
        $utilisateurId = $tableLog->getUtilisateur()->getId(); //int
        $dateHeure = $tableLog->getDateHeure(); //string
        $action = $tableLog->getAction(); //string
        $codeRetour = $tableLog->getCodeRetour(); //string
        $id = $tableLog->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO table_log (Utilisaeur_id, Machine_id, date_heure, action, codeRetour) '
                    . ' VALUES (?,?,?,?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('iisss',
                &$distribId,
                &$nomComplet,
                &$pseudo,
                &$visible,
                &$commentaire
            );
        }
        else
        {
            $sql = 'UPDATE table_log '
                    . 'SET Distrib_id = ?, '
                    . 'nom_complet = ?, '
                    . 'pseudo = ?, '
                    . 'commentaire = ?, '
                    . 'visible = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('isssbi',
                &$distribId,
                &$nomComplet,
                &$pseudo,
                &$commentaire,
                &$visible,
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
        $deleted = BaseSingleton::delete('DELETE FROM table_log WHERE id = ?', array('i', &$id));
        return $deleted;
    }

}

