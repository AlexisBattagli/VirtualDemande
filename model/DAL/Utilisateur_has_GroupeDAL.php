<?php


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
     * Retourne l'ensemble des Utilisateurs et leur rôle pour un Groupe_id passé en param
     * 
     * @param int $GroupeId
     * @return  Utilisateur_has_Groupe
     */
    public static function findByGroupe($groupeId)
    {
        $data = BaseSingleton::select('SELECT '
                        . ' Utilisateur_has_Groupe.Groupe_id as Groupe_id, '
                        . ' Utilisateur_has_Groupe.Utilisateur_id as Utilisateur_id, '
                        . ' Utilisateur_has_Groupe.role_groupe as role_groupe '
                        . ' FROM Utilisateur_has_Groupe'
                        . ' WHERE Utilisateur_has_Groupe.Groupe_id = ?', array('i', &$groupeId));
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
     * Retourne l'ensemble des Groupes et le rôle pour un Utilisateur_id passé en param
     * 
     * @param int $UtilisateurId
     * @return  Utilisateur_has_Groupe
     */
    public static function findByUtilisateur($utilisateurId)
    {
        $data = BaseSingleton::select('SELECT '
                        . ' Utilisateur_has_Groupe.Groupe_id as Groupe_id, '
                        . ' Utilisateur_has_Groupe.Utilisateur_id as Utilisateur_id, '
                        . ' Utilisateur_has_Groupe.role_groupe as role_groupe '
                        . ' FROM Utilisateur_has_Groupe'
                        . ' WHERE Utilisateur_has_Groupe.Utilisateur_id = ?', array('i', &$utilisateurId));
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

        $data = BaseSingleton::select('SELECT '
                        . ' Utilisateur_has_Groupe.Groupe_id as Groupe_id, '
                        . ' Utilisateur_has_Groupe.Utilisateur_id as Utilisateur_id, '
                        . ' Utilisateur_has_Groupe.role_groupe as role_groupe, '
                        . ' FROM Utilisateur_has_Groupe'
                . ' ORDER BY Utilisateur_has_Groupe.Groupe_id ASC, Utilisateur_has_Groupe.role_groupe ASC');

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
                        . ' Utilisateur_has_Groupe.Groupe_id as Groupe_id, '
                        . ' Utilisateur_has_Groupe.Utilisateur_id as Utilisateur_id, '
                        . ' Utilisateur_has_Groupe.role_groupe as role_groupe '
                        . ' FROM Utilisateur_has_Groupe'
                        . ' WHERE Utilisateur_has_Groupe.Groupe_id = ? AND Utilisateur_has_Groupe.Utilisateur_id = ?', array('ii', &$groupeId, &$utilisateurId));
        $utilisateurhasGroupe = new Utilisateur_has_Groupe();

        if (sizeof($data) > 0)
        {
            $utilisateurhasGroupe->hydrate($data[0]);
        }
        else
        {
            $utilisateurhasGroupe=null;
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
        if ($id < 0)
        {
            $sql = 'INSERT INTO Utilisateur_has_Groupe (Groupe_id, Utilisateur_id, role_groupe) '
                    . ' VALUES (?,?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('iis',
                &$groupeId,
                &$utilisateurId,
                &$roleGroupe
            );
        }
        else
        {
            $sql = 'UPDATE Utilisateur_has_Groupe '
                    . 'SET Groupe_id = ?, '
                    . 'Utilisateur_id = ?, '
                    . 'role_groupe = ? '
                    . 'WHERE Groupe_id = ? AND Utilisateur_id = ?';

            //Prépare les info concernant les type de champs
            $params = array('iisii',
                &$groupeId,
                &$utilisateurId,
                &$roleGroupe,
                &$groupeId,
                &$utilisateurId
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

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
        $deleted = BaseSingleton::delete('DELETE FROM Utilisateur_has_Groupe WHERE Groupe_id = ? AND Utilisateur_id = ?', array('ii', &$groupeId, &$utilisateurId));
        return $deleted;
    }
}
