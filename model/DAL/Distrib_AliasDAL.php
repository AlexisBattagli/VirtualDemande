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

}
