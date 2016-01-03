<?php

/*
 * Description of Distrib_AliasDAL
 *
 * @author Alexis
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Distrib_Alias.php');

class Distrib_AliasDAL {
    
    /*
     * Retourne l'alias d'une distrib correspondant à l'id donnée
     * 
     * @param int $id
     * @return Distrib_Alias
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT distrib_alias.id as id, '
                        . 'distrib_alias.Distrib_id as Distrib_id, '
                        . 'distrib_alias.nom_complet as nom_complet, '
                        . 'distrib_alias.pseudo as pseudo, '
                        . 'distrib_alias.commentaire as commentaire '
                        . ' FROM distrib_alias'
                        . ' WHERE distrib_alias.id = ?', array('i', &$id));
        $distribAlias = new Distrib_Alias();
        if (sizeof($data) > 0)
        {
            $distribAlias->hydrate($data[0]);
        }
        else
        {
            $distribAlias = null;
        }
        return $distribAlias;
    }

    /*
     * Retourne l'ensemble des Distrib_Alias qui sont en base
     * 
     * @return array[Distrib_Alias] Toutes les Distrib_Alias sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesDistribAlias = array();

        $data = BaseSingleton::select('SELECT distrib_alias.id as id, '
                        . 'distrib_alias.Distrib_id as Distrib_id, '
                        . 'distrib_alias.nom_complet as nom_complet, '
                        . 'distrib_alias.pseudo as pseudo, '
                        . 'distrib_alias.commentaire as commentaire'
                        . ' FROM distrib_alias '
                . ' ORDER BY distrib_alias.Distrib_id ASC, distrib_alias.nom_complet ASC');

        foreach ($data as $row)
        {
            $distribAlias = new Distrib_Alias();
            $distribAlias->hydrate($row);
            $mesDistribAlias[] = $distribAlias;
        }

        return $mesDistribAlias;
    }
    
    /*
     * Retourne la Distrib_Alias correspondant au couple Distrib_id/nom_complet
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur nom_complet
     * 
     * @param int distribId, string nomComplet
     * @return Distrib_Alias | null
     */
    public static function findByDN($distribId, $nomComplet)
    {
        $data = BaseSingleton::select('SELECT distrib_alias.id as id, '
                        . 'distrib_alias.Distrib_id as Distrib_id, '
                        . 'distrib_alias.nom_complet as nom_complet, '
                        . 'distrib_alias.pseudo as pseudo, '
                        . 'distrib_alias.commentaire as commentaire'
                        . ' FROM distrib_alias'
                        . ' WHERE distrib_alias.Distrib_id = ? AND LOWER(distrib_alias.nom_complet) = LOWER(?)', array('is', &$distribId, &$nomComplet));
        $distribAlias = new Distrib_Alias();

        if (sizeof($data) > 0)
        {
            $distribAlias->hydrate($data[0]);
        }
        return $distribAlias;
    }
}
