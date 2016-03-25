<?php

/**
 * Description of Table_log
 *
 * @author Alexis
 * @author Aurelie
 */
class Table_log {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */

    /*
     * Id d'un Table_log dans la table Table_log
     * @var int 
     */

    private $id;
    
    /*
     * Machine d'un Table_log dans la table Table_log
     * @var Machine 
     */

    private $machine;
    
    /*
     * Utilisateur d'un Table_log dans la table Table_log
     * @var Machine 
     */

    private $utilisateur;
    
    /*
     * dateHeure d'un Table_log dans la table Table_log
     * @var string 
     */

    private $dateHeure;
    
    /*
     * action d'un Table_log dans la table Table_log
     * @var string 
     */

    private $action;
    
    /*
     * codeRetour d'un Table_log dans la table Table_log
     * @var string 
     */

    private $codeRetour;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Table_log(
    $id = -1, $machine = null, $utilisateur = null, $dateHeure = "0000-00-00 00:00:00", $action = "Aucune action", $codeRetour = "Aucune code retour"
    )
    {
        $this->id = $id;
        if (is_null($machine))
        {
            $machine = MachineDAL::findDefaultMachine();
            $this->machine = $machine;
        }
        else
        {
            $this->machine = $machine;
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
        $this->dateHeure = $dateHeure;
        $this->action = $action;
        $this->codeRetour = $codeRetour;
    }

    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->machine = $dataSet['Machine_id'];
        $this->utilisateur = $dataSet['Utilisateur_id'];
        $this->dateHeure = $dataSet['date_heure'];
        $this->action = $dataSet['action'];
        $this->codeRetour = $dataSet['code_retour'];
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
    
    //Machine
    public function setMachine($machine)
    {
        if (is_string($machine))
        {
            $machine = (int) $machine;
            $this->machine = MachineDAL::findById($machine);
        }
        else if (is_int($machine))
        {
            $this->machine = MachineDAL::findById($machine);
        }
        else if (is_a($machine, "Machine"))
        {
            $this->machine = $machine;
        }
    }

    public function getMachine()
    {
        $machine = null;
        if (is_int($this->machine))
        {
            $machine = MachineDAL::findById($this->machine);
            $this->machine = $machine;
        }
        else if (is_a($this->machine, "Machine"))
        {
            $machine = $this->machine;
        }
        return $machine;
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
    
    //dateHeure
    public function setDateHeure($dateHeure)
    {
        if (is_string($dateHeure))
        {
            $this->dateHeure = $dateHeure;
        }
    }

    public function getDateHeure()
    {
        return $this->dateHeure;
    }
    
    //action
    public function setAction($action)
    {
        if (is_string($action))
        {
            $this->action = $action;
        }
    }

    public function getAction()
    {
        return $this->action;
    }
    
    //codeRetour
    public function setCodeRetour($codeRetour)
    {
        if (is_string($codeRetour))
        {
            $this->codeRetour = $codeRetour;
        }
    }

    public function getCodeRetour()
    {
        return $this->codeRetour;
    }
}
