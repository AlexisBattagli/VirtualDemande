<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilisateur_has_GroupeDAL
 *
 * @author Alexis
 * @author Aurelie
 */

require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur_has_Groupe.php');

class Utilisateur_has_GroupeDAL 
{
    /*
     * Retourne l'Utilisateur_has_Groupe correspondant à l'id donnée
     * 
     * @param int $id
     * @return  Utilisateur_has_Groupe
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT  Utilisateur_has_Groupe.id as id, '
                        . ' Utilisateur_has_Groupe.Groupe_id as Groupe, '
                        . ' Utilisateur_has_Groupe.Utilisateur_id as Utilisateur, '
                        . 'Utilisateur_has_Groupe.role_groupe as roleGroupe, '
                        . ' FROM Utilisateur_has_Groupe'
                        . ' WHERE Utilisateur_has_Groupe.id = ?', array('i', &$id));
        $UtilisateurhasGroupe = new Utilisateur_has_Groupe();
        if (sizeof($data) > 0)
        {
            $UtilisateurhasGroupe->hydrate($data[0]);
        }
        else
        {
            $UtilisateurhasGroupe = null;
        }
        return $UtilisateurhasGroupe;
    }

    /*
     * Retourne l'ensemble des Utilisateur_has_Groupe qui sont en base
     * 
     * @return array[Utilisateur_has_Groupe] Toutes les Utilisateur_has_Groupe sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesUtilisateur_has_Groupes = array();

        $data = BaseSingleton::select('SELECT  Utilisateur_has_Groupe.id as id, '
                        . ' Utilisateur_has_Groupe.Groupe_id as Groupe, '
                        . ' Utilisateur_has_Groupe.Utilisateur_id as Utilisateur, '
                        . 'Utilisateur_has_Groupe.role_groupe as roleGroupe, '
                        . ' FROM Utilisateur_has_Groupe'
                . ' ORDER BY Utilisateur_has_Groupe.Role_id ASC, Utilisateur_has_Groupe.Utilisateur_id ASC');

        foreach ($data as $row)
        {
            $utilisateurhasGroupe = new Utilisateur_has_Groupe();
            $utilisateurhasGroupe->hydrate($row);
            $mesUtilisateur_has_Groupes[] = $utilisateurhasGroupe;
        }

        return $mesUtilisateur_has_Groupes;
    }
    
    /*
     * Retourne le Utilisateur_has_Groupe correspondant au couple groupe/roleGroupe
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur roleGroupe
     * 
     * @param int groupe, string roleGroupe
     * @return Utilisateur_has_Groupe | null
     */
    public static function findByDN($groupe, $roleGroupe)
    {
        $data = BaseSingleton::select('SELECT  Utilisateur_has_Groupe.id as id, '
                        . ' Utilisateur_has_Groupe.Groupe_id as Groupe, '
                        . ' Utilisateur_has_Groupe.Utilisateur_id as Utilisateur, '
                        . 'Utilisateur_has_Groupe.role_groupe as roleGroupe, '
                        . ' FROM Utilisateur_has_Groupe'
                        . ' WHERE Utilisateur_has_Groupe.Groupe_id = ? AND LOWER(Utilisateur_has_Groupe.role_groupe) = LOWER(?)', array('is', &$groupe, &$roleGroupe));
        $utilisateurhasGroupe = new Utilisateur_has_Groupe();

        if (sizeof($data) > 0)
        {
            $utilisateurhasGroupe->hydrate($data[0]);
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
        $roleGroupe = $utilisateurhasGroupe->getRoleGroupe(); //string
        $id = $utilisateurhasGroupe->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO Utilisateur_has_Groupe (Role_id, Utilisateur_id, role_groupe) '
                    . ' VALUES (?,?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('isss',
                &$groupeId,
                &$utilisateurId,
                &$roleGroupe
            );
        }
        else
        {
            $sql = 'UPDATE Utilisateur_has_Groupe '
                    . 'SET Role_id = ?, '
                    . 'Utilisateur_id = ?, '
                    . 'role_groupe = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('isssi',
                &$groupeId,
                &$utilisateurId,
                &$roleGroupe,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime la Utilisateur_has_Groupe correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM Utilisateur_has_Groupe WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}
