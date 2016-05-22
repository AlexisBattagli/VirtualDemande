<?php

/*
 * Class Utilisateur
 * Possède un tableau des groupe auxquels appartient cette utilisateur,
 *  avec le role qu'il possède dans chacun de ses groupes
 * @version 0.1
 * @author Alexis 
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Role.php');

class Utilisateur {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */

    /*
     * Id d'un User dans la table Utlisateur
     * @var int 
     */

    private $id;

    /*
     * Role de l'utilisateur
     * @var Role
     */
    private $role;

    /*
     * Nom donnée à un user
     * @var string
     */
    private $nom;

    /*
     * prenom du user
     * @var string
     */
    private $prenom;

    /*
     * login du user
     * @var string
     */
    private $login;

    /*
     * password du user
     * @var string
     */
    private $password;

    /*
     * mail du user
     * @var string
     */
    private $mail;

    /*
     * date de la création de user
     * @var string
     */
    private $dateCreation;

    /*
     * date de naissance de user
     * @var string
     */
    private $dateNaissance;

    /*
     * nb_vm d'un User dans la table Utlisateur
     * @var int 
     */

    private $nbVm;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Utilisateur(
    $id = -1, $role = null, $nom = "Aucun Nom pour cet Utilisateur", $prenom = "Aucun Prenom pour cet Utilisateur", $login = "Aucun Login pour cet Utilisateur", $description = "Ce Groupe n'a pas de description", $password = "Aucun Password pour cet Utilisateur", $mail = "Aucun Mail pour cet Utilisateur", $dateCreation = "0000-00-00", $dateNaissance = "0000-00-00", $nbVm=0
    )
    {
        $this->id = $id;
        if (is_null($role))
        {
            $role = RoleDAL::findByDefault();
            $this->role = $role;
        }
        else
        {
            $this->role = $role;
        }
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->login = $login;
        $this->password = $password;
        $this->mail = $mail;
        $this->description = $description;
        $this->dateCreation = $dateCreation;
        $this->dateNaissance = $dateNaissance;
        $this->nbVm = $nbVm;
    }

    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->role = $dataSet['Role_id'];
        $this->nom = $dataSet['nom'];
        $this->prenom = $dataSet['prenom'];
        $this->login = $dataSet['login'];
        $this->password = $dataSet['password'];
        $this->mail = $dataSet['mail'];
        $this->dateCreation = $dataSet['date_creation'];
        $this->dateNaissance = $dataSet['date_naissance'];
        $this->nbVm = $dataSet['nb_vm'];
    }

    /*
      ==============================
      ======= GETTER/SETTER ========
      ==============================
     */

    //id
    public function setId($id)
    {
        if (is_int($id))
        {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }
    
    //role
    public function setRole($role)
    {
        if (is_string($role))
        {
            $role = (int) $role;
            $this->role = RoleDAL::findById($role);
        }
        else if (is_int($role))
        {
            $this->role = RoleDAL::findById($role);
        }
        else if (is_a($role, "Role"))
        {
            $this->role = $role;
        }
    }

    public function getRole()
    {
        $role = null;
        if (is_int($this->role))
        {
            $role = RoleDAL::findById($this->role);
            $this->role = $role;
        }
        else if (is_a($this->role, "Role"))
        {
            $role = $this->role;
        }
        return $role;
    }

    //nom
    public function setNom($nom)
    {
        if (is_string($nom))
        {
            $this->nom = $nom;
        }
    }

    public function getNom()
    {
        return $this->nom;
    }

    //prenom
    public function setPrenom($prenom)
    {
        if (is_string($prenom))
        {
            $this->prenom = $prenom;
        }
    }

    public function getPrenom()
    {
        return $this->prenom;
    }
    
    //login
    public function setLogin($login)
    {
        if (is_string($login))
        {
            $this->login = $login;
        }
    }

    public function getLogin()
    {
        return $this->login;
    }

    //password
    public function setPassword($password)
    {
        if (is_string($password))
        {
            $this->password = $password;
        }
    }

    public function getPassword()
    {
        return $this->password;
    }
    
    //mail
    public function setMail($mail)
    {
        if (is_string($mail))
        {
            $this->mail = $mail;
        }
    }

    public function getMail()
    {
        return $this->mail;
    }
    
    //date_creation
    public function setDateCreation($dateCreation)
    {
        if (is_string($dateCreation))
        {
            $this->dateCreation = $dateCreation;
        }
    }

    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    //date_naissance
    public function setDateNaissance($dateNaissance)
    {
        if (is_string($dateNaissance))
        {
            $this->dateNaissance = $dateNaissance;
        }
    }

    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }
    
    //nb_vm
    public function setNbVm($nbVm)
    {
        if (is_string($nbVm))
        {
            $this->nbVm = $nbVm;
        }
    }

    public function getNbVm()
    {
        return $this->nbVm;
    }
}
