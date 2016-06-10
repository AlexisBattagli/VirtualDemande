<?php

/**
 * Description of Utilisateur_has_GroupeDAL utilise la class Utilisateur_has_Groupe
 *
 * @author Alexis
 * @author Aurelie
 * @version 0.2
 * Histo:
 *     0.2 - Correction de ce que retourne findByGroupe et findByUtilisateur (liste de Utilisateur_hasGroupe)
 * 
 * Cette class permet de faire,
 * recherche, ajout, modification et suppression de Groupe et Utilisateur Lié
 * Permet de savoir quel sont les utilisateur d'un groupe.
 * quel sont les groupe uaxquels appartient un utilisateur
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur_has_Groupe.php');

class Utilisateur_has_GroupeDAL {
    /*
     * Retourne l'ensemble des Utilisateurs et leur rôle pour un Groupe_id passé en param
     * 
     * @param int $GroupeId
     * @return  Utilisateur_has_Groupe
     */

    public static function findByGroupe($groupeId)
    {
        $mesUtilisateur_has_Groupes = array();

        $data = BaseSingleton::select('SELECT '
                        . ' utilisateur_has_groupe.groupe_id as groupe_id, '
                        . ' utilisateur_has_groupe.utilisateur_id as utilisateur_id '
                        . ' FROM utilisateur_has_groupe'
                        . ' WHERE utilisateur_has_groupe.groupe_id = ?', array('i', &$groupeId));
        foreach ($data as $row)
        {
            $utilisateurhasGroupe = new Utilisateur_has_Groupe();
            $utilisateurhasGroupe->hydrate($row);
            $mesUtilisateur_has_Groupes[] = $utilisateurhasGroupe;
        }

        return $mesUtilisateur_has_Groupes;
    }

    /*
     * Retourne l'ensemble des Groupes et le rôle pour un Utilisateur_id passé en param
     * 
     * @param int $UtilisateurId
     * @return  Utilisateur_has_Groupe
     */

    public static function findByUtilisateur($utilisateurId)
    {
        $mesUtilisateur_has_Groupes = array();

        $data = BaseSingleton::select('SELECT '
                        . ' utilisateur_has_groupe.groupe_id as groupe_id, '
                        . ' utilisateur_has_groupe.utilisateur_id as utilisateur_id '
                        . ' FROM utilisateur_has_groupe'
                        . ' WHERE utilisateur_has_groupe.Utilisateur_id = ?', array('i', &$utilisateurId));

        foreach ($data as $row)
        {
            $utilisateurhasGroupe = new Utilisateur_has_Groupe();
            $utilisateurhasGroupe->hydrate($row);
            $mesUtilisateur_has_Groupes[] = $utilisateurhasGroupe;
        }

        return $mesUtilisateur_has_Groupes;
    }
    
    /*
     * Retourne l'ensemble des Groupes avec le nom et la description de ce dernier pour un Utilisateur_id passé en param
     * 
     * @param int $UtilisateurId
     * @return  Utilisateur_has_Groupe
     */

    public static function findByUser($utilisateurId)
    {
        $rows = array();

        $data = BaseSingleton::select('SELECT groupe.nom as nom, '
                .'groupe.description as description '
                .'FROM utilisateur_has_groupe, groupe '
                .'WHERE utilisateur_has_groupe.groupe_id = groupe.id AND utilisateur_has_groupe.utilisateur_id = ?', array('i', &$utilisateurId));
        
        foreach ($data as $row)
        {
            $rows[]=$row;
        }

        return $rows;
    }
    
        /*
     * Retourne l'ensemble des Groupes dont un Utilisateur_id passé en param n'est pas
     * 
     * @param int $UtilisateurId
     * @return  Utilisateur_has_Groupe
     */

    public static function findByNotUser($utilisateurId)
    {
        $rows = array();

        $data = BaseSingleton::select('SELECT groupe.nom as nom, '
                .'groupe.description as description, '
                .'groupe.id as id '
                .'FROM utilisateur_has_groupe, groupe '
                .'WHERE utilisateur_has_groupe.groupe_id = groupe.id AND utilisateur_has_groupe.utilisateur_id != ?', array('i', &$utilisateurId));
        
        foreach ($data as $row)
        {
            $rows[]=$row;
        }

        return $rows;
    }
    
    /*
     * Renvoie true ou false en fonction si l'utilisateur est dans le groupe ou non
     * 
     * @param int $userId, int $groupeId
     * return 0 | 1
     * 
     */
    
    public static function isInByUser($utilisateurId,$groupeId)
    {
        $utilisateurHasGroupe=  self::findByGU($groupeId, $utilisateurId);
        $statut=true;
        
        if(is_null($utilisateurHasGroupe))
        {
            $statut=false;
        }
        
        return $statut;
    }
    

    /*
     * Retourne l'ensemble des Utilisateur_has_Groupe qui sont en base
     * 
     * @return array[Utilisateur_has_Groupe] Toutes les Utilisateur_has_Groupe sont placées dans un Tableau
     */

    public static function findAll()
    {
        $mesUtilisateur_has_Groupes = array();

        $data = BaseSingleton::select('SELECT '
                        . ' utilisateur_has_groupe.groupe_id as groupe_id, '
                        . ' utilisateur_has_groupe.utilisateur_id as utilisateur_id '
                        . ' FROM utilisateur_has_groupe'
                        . ' ORDER BY utilisateur_has_groupe.groupe_id ASC, utilisateur_has_groupe.utilisateur_id');

        foreach ($data as $row)
        {
            $utilisateurhasGroupe = new Utilisateur_has_Groupe();
            $utilisateurhasGroupe->hydrate($row);
            $mesUtilisateur_has_Groupes[] = $utilisateurhasGroupe;
        }

        return $mesUtilisateur_has_Groupes;
    }

    /*
     * Retourne le Utilisateur_has_Groupe correspondant au couple groupe/utilisateur
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * 
     * @param int groupeId, string utilisateurId
     * @return Utilisateur_has_Groupe | null
     */

    public static function findByGU($groupeId, $utilisateurId)
    {
        $data = BaseSingleton::select('SELECT '
                        . ' utilisateur_has_groupe.groupe_id as groupe_id, '
                        . ' utilisateur_has_groupe.utilisateur_id as utilisateur_id '
                        . ' FROM utilisateur_has_groupe'
                        . ' WHERE utilisateur_has_groupe.groupe_id = ? AND utilisateur_has_groupe.utilisateur_id = ?', array('ii', &$groupeId, &$utilisateurId));
        $utilisateurhasGroupe = new Utilisateur_has_Groupe();

        if (sizeof($data) > 0)
        {
            $utilisateurhasGroupe->hydrate($data[0]);
        }
        else
        {
            $utilisateurhasGroupe = null;
        }
        return $utilisateurhasGroupe;
    }

    /*
     * Insère ou met à jour la Utilisateur_has_Groupe donnée en paramètre.
     * Pour cela on vérifie si l'id de la Utilisateur_has_Groupe transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Utilisateur_has_Groupe utilisateurhasGroupe
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($utilisateurhasGroupe)
    {
        
        //Récupère les valeurs de l'objet Utilisateur_has_Groupe passé en para de la méthode
        $groupeId = $utilisateurhasGroupe->getGroupe()->getId(); //int
        $utilisateurId = $utilisateurhasGroupe->getUtilisateur()->getId(); //int
        
        if (is_null(self::findByGU($groupeId, $utilisateurId)))
        {
            $sql = 'INSERT INTO utilisateur_has_groupe (groupe_id, utilisateur_id) '
                    . ' VALUES (?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('ii',
                &$groupeId,
                &$utilisateurId
            );
        }
        else
        {
            $sql = 'UPDATE utilisateur_has_groupe '
                    . 'SET groupe_id = ?, '
                    . 'utilisateur_id = ? '
                    . 'WHERE groupe_id = ? AND utilisateur_id = ?';

            //Prépare les info concernant les type de champs
            $params = array('iiii',
                &$groupeId,
                &$utilisateurId,
                &$groupeId,
                &$utilisateurId
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);
        echo "ok";
        return $idInsert;
    }

    /*
     * Supprime la Utilisateur_has_Groupe correspondant à le couple d'id de Groupe/Utilisateur donné en paramètre
     * 
     * @param int $groupeId, int utilisateurId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($groupeId, $utilisateurId)
    {
        $deleted = BaseSingleton::delete('DELETE FROM utilisateur_has_groupe WHERE Groupe_id = ? AND utilisateur_id = ?', array('ii', &$groupeId, &$utilisateurId));
        return $deleted;
    }
    
    /*
     * Supprime la Utilisateur_has_Groupe correspondant à l'id de Groupe donné en paramètre
     * 
     * @param int $groupeId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function deleteGroupe($groupeId)
    {
        $deleted = BaseSingleton::delete('DELETE FROM utilisateur_has_groupe WHERE Groupe_id = ?', array('i', &$groupeId));
        return $deleted;
    }
    
    /*
     * Supprime la Utilisateur_has_Groupe correspondant à l'id de Utilisateur donné en paramètre
     * 
     * @param int utilisateurId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function deleteUtilisateur( $utilisateurId)
    {
        $deleted = BaseSingleton::delete('DELETE FROM utilisateur_has_groupe WHERE utilisateur_id = ?', array('i', &$utilisateurId));
        return $deleted;
    }

}
