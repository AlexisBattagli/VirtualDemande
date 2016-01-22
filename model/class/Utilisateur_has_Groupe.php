<?php

/*
 * Description of Utilisateur_has_Groupe
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Role.php');

class Utilisateur_has_Groupe {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */

    /*
     * Id d'un Utilisateur_has_Groupe dans la table Utilisateur_has_Groupe
     * @var int 
     */

    private $id;

    /*
     * Role de la classe Role
     * @var Role
     */
    private $role;

    /*
     * Utilisateur de la classe Utilisateur 
     * @var Utilisateur
     */
    private $utilisateur ;

    /*
     * role_groupe d'un Utilisateur_has_Groupe
     * @var string
     */
    private $roleGroupe;

    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Utilisateur_has_Groupe(
    $id = -1, $role = null, $utilisateur= null, $roleGroupe = "Aucun nom role pour cet Utilisateur_has_Groupe"
    )
    {
        $this->id = $id;
        if (is_null($role))
        {
            $role = RoleDAL::findDefaultRole();
            $this->role = $role;
        }
        else
        {
            $this->role = $role;
        }
	if (is_null($utilisateur))
        {
            $utilisateur = UtilisateurDAL::findDefaultUtilisateur();
            $this->utilisateur = $utilisateur;
        }
        else
        {
            $this->utilisateur = $utilisateur;
        }
        $this->roleGroupe = $roleGroupe;
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
	$this->utilisateur = $dataSet['Utilisateur_id'];
        $this->roleGroupe= $dataSet['role_groupe'];
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

    //Role
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

    //Utilisateur
    public function setUtilisateur($utilisateur)
    {
        if (is_string($utilisateur))
        {
            $utilisateur = (int) $utilisateur;
            $this->utilisateur = UtilisateurDAL::findById($utilisateur);
        }
        else if (is_int($utilisateur))
        {
            $this->utilisateur = UtilisateurDAL::findById($utilisateur);
        }
        else if (is_a($utilisateur, "Utilisateur"))
        {
            $this->utilisateur = $utilisateur;
        }
    }

    public function getUtilisateur()
    {
        $utilisateur = null;
        if (is_int($this->utilisateur))
        {
            $utilisateur = UtilisateurDAL::findById($this->utilisateur);
            $this->utilisateur = $utilisateur;
        }
        else if (is_a($this->utilisateur, "Utilisateur"))
        {
            $utilisateur = $this->utilisateur;
        }
        return $utilisateur;
    }

    //role_groupe
    public function setRoleGroupe($roleGroupe)
    {
        if (is_string($roleGroupe))
        {
            $this->roleGroupe = $roleGroupe;
        }
    }

    public function getRoleGroupe()
    {
        return $this->roleGroupe;
    }
}