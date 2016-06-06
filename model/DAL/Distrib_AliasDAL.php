<?php

/*
 * Description of Distrib_AliasDAL
 *
 * @author Alexis
 * @author Aurelie
 */

/*
 * IMPORT
 */
require_once('BaseSingleton.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Distrib_Alias.php');
require_once('/var/www/VirtualDemande/model/class/Distrib_Alias.php');

class Distrib_AliasDAL {
    
    /*
     * Retourne l'alias d'une distrib par défaut
     * 
     * @return Distrib_Alias
     */
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT distrib_alias.id as id, '
                        . 'distrib_alias.distrib_id as distrib_id, '
                        . 'distrib_alias.nom_complet as nom_complet, '
                        . 'distrib_alias.pseudo as pseudo, '
                        . 'distrib_alias.commentaire as commentaire, '
                        . 'distrib_alias.visible as visible '
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
     * Retourne l'alias d'une distrib correspondant à l'id donnée
     * 
     * @param int $id
     * @return Distrib_Alias
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT distrib_alias.id as id, '
                        . 'distrib_alias.distrib_id as distrib_id, '
                        . 'distrib_alias.nom_complet as nom_complet, '
                        . 'distrib_alias.pseudo as pseudo, '
                        . 'distrib_alias.commentaire as commentaire, '
                        . 'distrib_alias.visible as visible '
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
     * Retourne l'ensemble des Distrib_Alias qui sont visible
     * 
     * @return array[Distrib_Alias] Toutes les Distrib_Alias sont placées dans un Tableau
     */
    public static function findByVisible()
    {
        $mesDistribAlias = array();

        $data = BaseSingleton::select('SELECT distrib_alias.id as id, '
                        . 'distrib_alias.distrib_id as distrib_id, '
                        . 'distrib_alias.nom_complet as nom_complet, '
                        . 'distrib_alias.pseudo as pseudo, '
                        . 'distrib_alias.commentaire as commentaire, '
                        . 'distrib_alias.visible as visible '
                        . ' FROM distrib_alias '
                . ' WHERE distrib_alias.visible = 0');

        foreach ($data as $row)
        {
            $distribAlias = new Distrib_Alias();
            $distribAlias->hydrate($row);
            $mesDistribAlias[] = $distribAlias;
        }

        return $mesDistribAlias;
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
                        . 'distrib_alias.distrib_id as distrib_id, '
                        . 'distrib_alias.nom_complet as nom_complet, '
                        . 'distrib_alias.pseudo as pseudo, '
                        . 'distrib_alias.commentaire as commentaire, '
                        . 'distrib_alias.visible as visible '
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
                        . 'distrib_alias.distrib_id as distrib_id, '
                        . 'distrib_alias.nom_complet as nom_complet, '
                        . 'distrib_alias.pseudo as pseudo, '
                        . 'distrib_alias.commentaire as commentaire, '
                        . 'distrib_alias.visible as visible '
                        . ' FROM distrib_alias'
                        . ' WHERE distrib_alias.distrib_id = ? AND LOWER(distrib_alias.nom_complet) = LOWER(?)', array('is', &$distribId, &$nomComplet));
        $distribAlias = new Distrib_Alias();

        if (sizeof($data) > 0)
        {
            $distribAlias->hydrate($data[0]);
        }
        else 
        {
            $distribAlias=null;
        }
         return $distribAlias;
    }
    
    /*
     * Insère ou met à jour la Distrib_Alias donnée en paramètre.
     * Pour cela on vérifie si l'id de la Distrib_Alias transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Distri_Alias distribAlias
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($distribAlias)
    {

        //Récupère les valeurs de l'objet distrib_alias passé en para de la méthode
        $distribId = $distribAlias->getDistrib()->getId(); //int
        $nomComplet = $distribAlias->getNomComplet(); //string
        $pseudo = $distribAlias->getPseudo(); //string
        $commentaire = $distribAlias->getCommentaire(); //string
        $visible = $distribAlias->getVisible();//int
        $id = $distribAlias->getId(); //int
        
        if ($id < 0)
        {
            $sql = 'INSERT INTO distrib_alias (distrib_id, nom_complet, pseudo, commentaire, visible) '
                    . ' VALUES (?,?,?,?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('isssi',
                &$distribId,
                &$nomComplet,
                &$pseudo,
                &$commentaire,
                &$visible,
            );
        }
        else
        {
            $sql = 'UPDATE distrib_alias '
                    . 'SET distrib_id = ?, '
                    . 'nom_complet = ?, '
                    . 'pseudo = ?, '
                    . 'commentaire = ?, '
                    . 'visible = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('isssii',
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
     * Supprime la Distrib_Alias correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM distrib_alias WHERE id = ?', array('i', &$id));
        return $deleted;
    }

}
