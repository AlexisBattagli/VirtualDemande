<?php

/**
 * Description of UtilisateurDAL
 *
 * @author Alexis
 * @author Aurelie
 */

require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur.php');

class UtilisateurDAL 
{
    /*
     * Retourne l'utilisateur correspondant à l'id donné
     * 
     * @param int $id
     * @return Utilisateur
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT utilisateur.id as id, '
                        . 'utilisateur.Role_id as Role_id, '
                        . 'utilisateur.nom as nom, '
                        . 'utilisateur.prenom as prenom, '
                        . 'utilisateur.login as login, '
                        . 'utilisateur.password as password, '
                        . 'utilisateur.mail as mail, '
                        . 'utilisateur.date_creation as date_creation, '
                        . 'utilisateur.date_naissance as date_naissance, '
                        . 'utilisateur.nb_vm as nb_vm '
                        . ' FROM utilisateur'
                        . ' WHERE utilisateur.id = ?', array('i', &$id));
        $utilisateur = new Utilisateur();
        if (sizeof($data) > 0)
        {
            $utilisateur->hydrate($data[0]);
        }
        else
        {
            $utilisateur = null;
        }
        return $utilisateur;
    }

    /*
     * Retourne l'ensemble des Utilisateurs qui sont en base
     * 
     * @return array[Utilisateur] Tous les Utilisateurs sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesUtilisateurs = array();

        $data = BaseSingleton::select('SELECT utilisateur.id as id, '
                        . 'utilisateur.Role_id as Role_id, '
                        . 'utilisateur.nom as nom, '
                        . 'utilisateur.prenom as prenom, '
                        . 'utilisateur.login as login, '
                        . 'utilisateur.password as password, '
                        . 'utilisateur.mail as mail, '
                        . 'utilisateur.date_creation as date_creation, '
                        . 'utilisateur.date_naissance as date_naissance, '
                        . 'utilisateur.nb_vm as nb_vm '
                        . ' FROM utilisateur'
                . ' ORDER BY utilisateur.Role_id ASC, utilisateur.nom ASC, utilisateur.prenom ASC, utilisateur.login ASC');

        foreach ($data as $row)
        {
            $utilisateur = new Utilisateur();
            $utilisateur->hydrate($row);
            $mesUtilisateurs[] = $utilisateur;
        }
        return $mesUtilisateurs;
    }
    
    /*
     * Retourne l'Utilisateur correspondant au couple login
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est rechercher sans tenir compte de la casse sur login
     * 
     * @param string login
     * @return Utilisateur | null
     */
    public static function findByLogin($login)
    {
        $data = BaseSingleton::select('SELECT utilisateur.id as id, '
                        . 'utilisateur.Role_id as Role_Id, '
                        . 'utilisateur.nom as nom, '
                        . 'utilisateur.prenom as prenom, '
                        . 'utilisateur.login as login, '
                        . 'utilisateur.password as password, '
                        . 'utilisateur.mail as mail, '
                        . 'utilisateur.dateCreation as date_creation, '
                        . 'utilisateur.date_naissance as date_naissance, '
                        . 'utilisateur.nb_vm as nb_vm '
                        . ' FROM utilisateur'
                        . ' WHERE LOWER(utilisateur.login) = LOWER(?)', array('s', &$login));
        $utilisateur = new Utilisateur();

        if (sizeof($data) > 0)
        {
            $utilisateur->hydrate($data[0]);
        }
        else
        {
            $utilisateur=null;
        }
        return $utilisateur;
    }
    
    /*
     * Insère ou met à jour l'Utilisateur donné en paramètre.
     * Pour cela on vérifie si l'id de la Distrib_Alias transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Utilisateur utilisateur
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($utilisateur)
    {

        //Récupère les valeurs de l'objet utilisateur passé en para de la méthode
        $role = $utilisateur->getRole()->getId(); //int
        $nom = $utilisateur->getNom(); //string
        $prenom = $utilisateur->getPrenom(); //string
        $login = $utilisateur->getLogin(); //string
        $password = $utilisateur->getPassword(); //string
        $mail = $utilisateur->getMail(); //string
        $dateCreation = $utilisateur->getDateCreation(); //string
        $dateNaissance = $utilisateur->getDateNaissance(); //string
        $nbVM = $nbVM->getNbVm();
        $id = $utilisateur->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO utilisateur (Role_id, nom, prenom, login, password, mail, date_creation, date_naissance, nb_vm) '
                    . ' VALUES (?,?,?,?,?,?,?,?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('isssssssi',
                &$role,
                &$nom,
                &$prenom,
                &$login,
                &$password,
                &$mail,
                &$dateCreation,
                &$dateNaissance,
                &$nbVM
            );
        }
        else
        {
            $sql = 'UPDATE utilisateur '
                    . 'SET Role_id = ?, '
                    . 'nom = ?, '
                    . 'prenom = ?, '
                    . 'login = ?, '
                    . 'password = ?, '
                    . 'mail = ?, '
                    . 'date_creation = ?, '
                    . 'date_naissance = ?, '
                    . 'nb_vm = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('isssssssii',
                &$role,
                &$nom,
                &$prenom,
                &$login,
                &$password,
                &$mail,
                &$dateCreation,
                &$dateNaissance,
                &$nb_vm,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime l'Utilisateur correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM utilisateur WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}
