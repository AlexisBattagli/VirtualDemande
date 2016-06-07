<?php

/**
 * Description of UtilisateurDAL
 *
 * @author Alexis
 * @author Aurelie
 */

require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/LimitantDAL.php');

class UtilisateurDAL 
{
    /*
     * Retourne l'utilisateur par défaut
     * 
     * @return Utilisateur
     */
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT utilisateur.id as id, '
                        . 'utilisateur.Role_id as Role_id, '
                        . 'utilisateur.nom as nom, '
                        . 'utilisateur.prenom as prenom, '
                        . 'utilisateur.login as login, '
                        . 'utilisateur.passwd as passwd, '
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
                        . 'utilisateur.passwd as passwd, '
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
                        . 'utilisateur.passwd as passwd, '
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
                        . 'utilisateur.passwd as passwd, '
                        . 'utilisateur.mail as mail, '
                        . 'utilisateur.date_creation as date_creation, '
                        . 'utilisateur.date_naissance as date_naissance, '
                        . 'utilisateur.nb_vm as nb_vm '
                        . ' FROM utilisateur'
                        . ' WHERE utilisateur.login = ?', array('s', &$login));
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
     * Retourne l'Utilisateur correspondant au couple $mail
     * Il est rechercher sans tenir compte de la casse sur mail
     * 
     * @param string mail
     * @return Utilisateur | null
     */
    public static function findByMail($mail)
    {
        $data = BaseSingleton::select('SELECT utilisateur.id as id, '
                        . 'utilisateur.Role_id as Role_Id, '
                        . 'utilisateur.nom as nom, '
                        . 'utilisateur.prenom as prenom, '
                        . 'utilisateur.login as login, '
                        . 'utilisateur.passwd as passwd, '
                        . 'utilisateur.mail as mail, '
                        . 'utilisateur.date_creation as date_creation, '
                        . 'utilisateur.date_naissance as date_naissance, '
                        . 'utilisateur.nb_vm as nb_vm '
                        . ' FROM utilisateur'
                        . ' WHERE LOWER(utilisateur.mail) = LOWER(?)', array('s',&$mail));
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
     * Renvoie la liste de SES vm partagé (nom de la vm, desc, groupe dans lequel elle est partagé)
     * 
     * @param userId
     * return Object
     */
    
    public static function findShareContener($userId)
    {
        $rows = array();
        $data = BaseSingleton::select('SELECT machine.nom as nom, '
                .'distrib_alias.nom_complet as os, '
                .'machine.description as description '
                .'FROM machine, distrib_alias, groupe_has_machine, utilisateur  '
                .'WHERE machine.distrib_alias_id = distrib_alias.id '
                .'AND groupe_has_machine.machine_id=machine.id '
                .'AND machine.utilisateur_id=utilisateur.id '
                .'AND utilisateur.id = ?', array('i', &$userId));
        
        foreach ($data as $row)
        {
            $rows[]=$row;
        }

        return $rows;
    }
    
    /*
     * Retourne True si le nom d'utilisateur et le mot de passe sont bons
     * 
     * @param string $login, string $password
     * @return 1 | 0
     */
    
    public static function connection($login,$password) 
    {
        $data = BaseSingleton::select('SELECT utilisateur.id as id, '
                        . 'utilisateur.Role_id as Role_Id, '
                        . 'utilisateur.nom as nom, '
                        . 'utilisateur.prenom as prenom, '
                        . 'utilisateur.login as login, '
                        . 'utilisateur.passwd as passwd, '
                        . 'utilisateur.mail as mail, '
                        . 'utilisateur.date_creation as date_creation, '
                        . 'utilisateur.date_naissance as date_naissance, '
                        . 'utilisateur.nb_vm as nb_vm '
                        . ' FROM utilisateur'
                        . ' WHERE utilisateur.login = ? AND utilisateur.passwd = ?', array('ss',&$login,&$password));

        if (sizeof($data) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    /* 
     * Renvoie le nb d’utilisateurs restant 
     *
     * @return int
     */
    
    public static function GetNumberAvailableUsers()
    {
        $datanbreMax = BaseSingleton::select('SELECT * FROM limitant WHERE limitant.id = 1');
        $limitant=new Limitant();
        if (sizeof($datanbreMax) > 0)
        {
            $limitant->hydrate($datanbreMax[0]);
        }
        $nbreMax=$limitant->getNbUserMax();
        
        $datanbreActuel = BaseSingleton::select('SELECT * FROM utilisateur');
        $nbreActuel=0;
        foreach ($datanbreActuel as $row)
        {
            $utilisateur = new Utilisateur();
            $utilisateur->hydrate($row);
            $nbreActuel=$nbreActuel+1;
        }
        $nbreDispo=-1;
        
        if(is_int($nbreMax)&&(is_int($nbreDispo)))
        {
            $nbreDispo=$nbreMax-$nbreActuel;
        }
        
        return $nbreDispo;
    }
    
    /*
     * Vérifie que l'utilisateur créé n'a pas le même login ou la même adresse mail que quelqu'un d'autre
     * 
     * @param sting login, string mail
     * return 0 | 1
     */
    
    public static function isUnique($login,$mail)
    {
        $findUserLogin = self::findByLogin($login);
        $findUserMail = self::findByMail($mail);
        $statut = 1;
        
        if(($findUserLogin==null)&&($findUserMail==null))
        {
            $statut=0;
        }
        
        return $statut;
    }
    
    /* 
     * Retourne pour un utilisateur s'il a la posibilité de créer une nouvelle machine
     * 
     * @param int userId
     * @return bool
     */
    
    public static function isFull($userId)
    {
        $datanbreMax = BaseSingleton::select('SELECT * FROM limitant WHERE limitant.id = 1');
        $limitant=new Limitant();
        if (sizeof($datanbreMax) > 0)
        {
            $limitant->hydrate($datanbreMax[0]);
        }
        $nbreMax=$limitant->getNbVMUser();
        
        $datanbreActuel = BaseSingleton::select('SELECT * FROM utilisateur WHERE id = ?', array('i', &$userId));
        $utilisateur=new Utilisateur();
        if (sizeof($datanbreActuel) > 0)
        {
            $utilisateur->hydrate($datanbreActuel[0]);
        }
        $nbreActuel=$utilisateur->getNbVm();
        $statut=false;
        
        if(is_int($nbreActuel)&&(is_int($nbreMax)))
        {
            $nbreRestant=$nbreMax-$nbreActuel;
            if($nbreRestant>=1)
            {
                $statut=true;
            }
        }
        
        return $statut;
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
        $nbVM = $utilisateur->getNbVm();
        $id = $utilisateur->getId(); //int
        
        if ($id < 0)
        {
            $sql = 'INSERT INTO utilisateur (Role_id, nom, prenom, login, passwd, mail, date_creation, date_naissance, nb_vm) '
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
                    . 'passwd = ?, '
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
                &$nbVM,
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
