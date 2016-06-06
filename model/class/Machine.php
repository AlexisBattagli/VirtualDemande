<?php

/**
 * Description of Machine
 *
 * @author Alexis
 * @author Aurelie
 * 
 * @version 0.1
 * @history 
 */
//import
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Distrib_AliasDAL.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/CpuDAL.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/RamDAL.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/StockageDAL.php');
require_once('/var/www/VirtualDemande/model/DAL/Distrib_AliasDAL.php');
require_once('/var/www/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once('/var/www/VirtualDemande/model/DAL/CpuDAL.php');
require_once('/var/www/VirtualDemande/model/DAL/RamDAL.php');
require_once('/var/www/VirtualDemande/model/DAL/StockageDAL.php');        

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
     * cpu d'une Machine dans la table Machine
     * @var Cpu
     */
    private $cpu;
    
    /*
     * ram d'une Machine dans la table Machine
     * @var Ram
     */
    private $ram;

    /*
     * stockage d'une Machine dans la table Machine
     * @var Stockage
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
     * date_expiration d'une Machine dans la table Machine
     * @var string
     */
    private $dateExpiration;
    
    /*
     * etat d'une Machine dans la table Machine
     * @var int
     */
    private $etat;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Machine(
    $id = -1, $distribAlias = null, $utilisateur = null, $nom = "Aucun nom pour cette machine", $cpu = null, $ram = null, $stockage = null, $description = "Cette Machine n'a pas de description", $dateCreation = "0000-00-00", $dateExpiration = "0000-00-00", $etat=-1
    )
    {
        $this->id = $id;
        if (is_null($utilisateur))
        {
            $utilisateur = UtilisateurDAL::findByDefault();
            $this->utilisateur = $utilisateur;
        }
        else
        {
            $this->utilisateur = $utilisateur;
        }
        if (is_null($distribAlias))
        {
            $distribAlias = Distrib_AliasDAL::findByDefault();
            $this->distribAlias = $distribAlias;
        }
        else
        {
            $this->distribAlias = $distribAlias;
        }
        $this->nom = $nom;
        if (is_null($cpu))
        {
            $cpu = CpuDAL::findByDefault();
            $this->cpu = $cpu;
        }
        else
        {
            $this->cpu = $cpu;
        }
        if (is_null($ram))
        {
            $ram = RamDAL::findByDefault();
            $this->ram = $ram;
        }
        else
        {
            $this->ram = $ram;
        }
        if (is_null($stockage))
        {
            $stockage = StockageDAL::findByDefault();
            $this->stockage = $stockage;
        }
        else
        {
            $this->stockage = $stockage;
        }
        $this->description = $description;
        $this->dateCreation = $dateCreation;
        $this->dateExpiration = $dateExpiration;
        $this->etat = $etat;
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
        $this->cpu = $dataSet['Cpu_id'];
        $this->ram = $dataSet['Ram_id'];
        $this->stockage = $dataSet['Stockage_id'];
        $this->description = $dataSet['description'];
        $this->dateCreation = $dataSet['date_creation'];
        $this->dateExpiration = $dataSet['date_expiration'];
        $this->etat = $dataSet['etat'];
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
        if (is_string($ram))
        {
            $ram = (int) $ram;
            $this->ram = RamDAL::findById($ram);
        }
        else if (is_int($ram))
        {
            $this->ram = RamDAL::findById($ram);
        }
        else if (is_a($ram, "Ram"))
        {
            $this->ram = $ram;
        }
    }

    public function getRam()
    {
        $ram = null;
        if (is_int($this->ram))
        {
            $ram = RamDAL::findById($this->ram);
            $this->ram = $ram;
        }
        else if (is_a($this->ram, "Ram"))
        {
            $ram = $this->ram;
        }
        return $ram;
    }

    //cpu
    public function setCpu($cpu)
    {
        if (is_string($cpu))
        {
            $cpu = (int) $cpu;
            $this->cpu = CpuDAL::findById($cpu);
        }
        else if (is_int($cpu))
        {
            $this->cpu = CpuDAL::findById($cpu);
        }
        else if (is_a($cpu, "Cpu"))
        {
            $this->cpu = $cpu;
        }
    }

    public function getCpu()
    {
        $cpu = null;
        if (is_int($this->cpu))
        {
            $cpu = CpuDAL::findById($this->cpu);
            $this->cpu = $cpu;
        }
        else if (is_a($this->cpu, "Cpu"))
        {
            $cpu = $this->cpu;
        }
        return $cpu;
    }

    //stockage
    public function setStockage($stockage)
    {
        if (is_string($stockage))
        {
            $stockage = (int) $stockage;
            $this->stockage = StockageDAL::findById($stockage);
        }
        else if (is_int($stockage))
        {
            $this->stockage = StockageDAL::findById($stockage);
        }
        else if (is_a($stockage, "Stockage"))
        {
            $this->stockage = $stockage;
        }
    }

    public function getStockage()
    {
        $stockage = null;
        if (is_int($this->stockage))
        {
            $stockage = StockageDAL::findById($this->stockage);
            $this->stockage = $stockage;
        }
        else if (is_a($this->stockage, "Stockage"))
        {
            $stockage = $this->stockage;
        }
        return $stockage;
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
       return $this->description;
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
    
    //dateExpiration
    public function setDateExpiration($dateExpiration)
    {
        if (is_string($dateExpiration))
        {
            $this->dateExpiration = $dateExpiration;
        }
    }

    public function getDateExpiration()
    {
        return $this->dateExpiration;
    }
    
    //etat
    public function setEtat($etat)
    {
        if (is_int($etat))
        {
            $this->etat = $etat;
        }
    }

    public function getEtat()
    {
        return $this->etat;
    }
}
