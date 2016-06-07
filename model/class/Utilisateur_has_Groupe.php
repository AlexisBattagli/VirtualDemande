<?php

/*
 * Description of Utilisateur_has_Groupe
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');

class Utilisateur_has_Groupe {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */

    /*
     * Groupe de la classe Groupe
     * @var Groupe
     */
    private $groupe;

    /*
     * Utilisateur de la classe Utilisateur 
     * @var Utilisateur
     */
    private $utilisateur;

    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Utilisateur_has_Groupe(
    $groupe = null, $utilisateur= null
    )
    {
        if (is_null($groupe))
        {
            $groupe = GroupeDAL::findByDefault();
            $this->groupe = $groupe;
        }
        else
        {
            $this->groupe = $groupe;
        }
	if (is_null($utilisateur))
        {
            $utilisateur = UtilisateurDAL::findByDefault();
            $this->utilisateur = $utilisateur;
        }
        else
        {
            $this->utilisateur = $utilisateur;
        }
    }

	/*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->groupe = $dataSet['groupe_id'];
	$this->utilisateur = $dataSet['utilisateur_id'];
    }

    /*
      ==============================
      ======= GETTER/SETTER ========
      ==============================
     */

    //Groupe
    public function setGroupe($groupe)
    {
        if (is_string($groupe))
        {
            $groupe = (int) $groupe;
            $this->groupe = GroupeDAL::findById($groupe);
        }
        else if (is_int($groupe))
        {
            $this->groupe = GroupeDAL::findById($groupe);
        }
        else if (is_a($groupe, "Groupe"))
        {
            $this->groupe = $groupe;
        }
    }

    public function getGroupe()
    {
        $groupe = null;
        if (is_int($this->groupe))
        {
            $groupe = GroupeDAL::findById($this->groupe);
            $this->groupe = $groupe;
        }
        else if (is_a($this->groupe, "Groupe"))
        {
            $groupe = $this->groupe;
        }
        return $groupe;
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
}