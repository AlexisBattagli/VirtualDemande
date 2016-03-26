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
     * @var string 
     */

    private $machine;
    
    /*
     * Utilisateur d'un Table_log dans la table Table_log
     * @var string 
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
    $id = -1, $machine = "Aucune Machine", $utilisateur = "Aucun utilisateur", $dateHeure = "0000-00-00 00:00:00", $action = "Aucune action", $codeRetour = "Aucune code retour"
    )
    {
        $this->id = $id;
        $this->machine = $machine;
        $this->utilisateur = $utilisateur;
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
        $this->machine = $dataSet['machine'];
        $this->utilisateur = $dataSet['utilisateur'];
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
            $this->machine = $machine;
        }
    }

    public function getMachine()
    {
        return $this->machine;
    }
    
    //Utilisateur
    public function setUtilisateur($utilisateur)
    {
        if (is_string($utilisateur))
        {
            $this->utilisateur = $utilisateur;
        }
    }

    public function getUtilisateur()
    {
        return $this->utilisateur;
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
