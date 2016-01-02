<?php

/**
 * Description of Machine
 *
 * @author Alexis
 * @author Aurelie
 */
//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Distrib_Alias.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur.php');

class Machine {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */

    /*
     * Id d'une Machine dans la table Machine
     * @var int 
     */

    private $id;

    /*
     * Utilisateur de la table Machine
     * @var Utilisateur
     */
    private $utilisateur;

    /*
     * Distrib alias de la table Machine
     * @var Distrib_Alias
     */
    private $distribAlias;

    /*
     * nom d'une Machine dans la table Machine
     * @var string
     */
    private $nom;

    /*
     * ram d'une Machine dans la table Machine
     * @var float
     */
    private $ram;

    /*
     * coeur d'une Machine dans la table Machine
     * @var int
     */
    private $coeur;

    /*
     * stockage d'une Machine dans la table Machine
     * @var float
     */
    private $stockage;

    /*
     * description d'une Machine dans la table Machine
     * @vat string
     */
    private $description;

    /*
     * date_creation d'une Machine dans la table Machine
     * @var string
     */
    private $dateCreation;

    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Machine(
    $id = -1, $distribAlias = null, $utilisateur = null, $nom = "Aucun nom pour cette machine", $ram = -1, $coeur = -1, $stockage = -1, $description = "Cette Machine n'a pas de description", $dateCreation = "0000-00-00"
    )
    {
        $this->id = $id;
        if (is_null($utilisateur))
        {
            $utilisateur = UtilisateurDAL::findDefaultUtilisateur();
            $this->utilisateur = $utilisateur;
        }
        else
        {
            $this->utilisateur = $utilisateur;
        }
        if (is_null($distribAlias))
        {
            $distribAlias = Distrib_AliasDAL::findDefaultDistribAlias();
            $this->distribAlias = $distribAlias;
        }
        else
        {
            $this->distribAlias = $distribAlias;
        }
        $this->nom = $nom;
        $this->ram = $ram;
        $this->coeur = $coeur;
        $this->stockage = $stockage;
        $this->description = $description;
        $this->dateCreation = $dateCreation;
    }

    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->utilisateur = $dataSet['Utilisateur_id'];
        $this->distribAlias = $dataSet['Distrib_Alias_id'];
        $this->nom = $dataSet['nom'];
        $this->ram = $dataSet['ram'];
        $this->coeur = $dataSet['coeur'];
        $this->stockage = $dataSet['stockage'];
        $this->description = $dataSet['description'];
        $this->dateCreation = $dataSet['date_creation'];
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
            $this->utilisateur = UtiliateurDAL::findById($utilisateur);
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

    //DitribAlias
    public function setDistribAlias($distribAlias)
    {
        if (is_string($distribAlias))
        {
            $distribAlias = (int) $distribAlias;
            $this->distribAlias = Distrib_AliasDAL::findById($distribAlias);
        }
        else if (is_int($distribAlias))
        {
            $this->distribAlias = Distrib_AliasDAL::findById($distribAlias);
        }
        else if (is_a($distribAlias, "Distrib_Alias"))
        {
            $this->distribAlias = $distribAlias;
        }
    }

    public function getDistribAlias()
    {
        $distribAlias = null;
        if (is_int($this->distribAlias))
        {
            $distribAlias = Distrib_AliasDAL::findById($this->distribAlias);
            $this->distribAlias = $distribAlias;
        }
        else if (is_a($this->distribAlias, "Distrib_Alias"))
        {
            $distribAlias = $this->distribAlias;
        }
        return $distribAlias;
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

    //ram
    public function setRam($ram)
    {
        if (is_float($ram))
        {
            $this->ram = $ram;
        }
    }

    public function getRam()
    {
        return $this->ram;
    }

    //coeur
    public function setCoeur($coeur)
    {
        if (is_int($coeur))
        {
            $this->coeur = $coeur;
        }
    }

    public function getCoeur()
    {
        return $this->coeur;
    }

    //stockage
    public function setStockage($stockage)
    {
        if (is_float($stockage))
        {
            $this->stockage = $stockage;
        }
    }

    public function getStockage()
    {
        return $this->stockage;
    }

    //description
    public function setDescription($description)
    {
        if (is_string($description))
        {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        $this->description;
    }

    //dateCreation
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

}
