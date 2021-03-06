<?php

 /*
 * Description of Utilisateur_has_Groupe
 *
 * @author Alexis
 * @author Aurelie
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');

class Groupe_has_Machine {
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
     * Utilisateur de la classe Machine
     * @var Machine
     */
    private $machine;
    
    /*
     * commentaire de la Machine
     * @vat string
     */
    private $commentaire;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Groupe_has_Machine($groupe = null, $machine= null, $commentaire = "Cette Distrib Alias n'a pas de commentaire")
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
	if (is_null($machine))
        {
            $machine = MachineDAL::findByDefault();
            $this->machine = $machine;
        }
        else
        {
            $this->machine = $machine;
        }
        $this->commentaire = $commentaire;
    }
    
    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->groupe = $dataSet['groupe_id'];
	$this->machine = $dataSet['machine_id'];
        $this->commentaire = $dataSet['commentaire'];
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
    
    //commentaire
    public function setCommentaire($commentaire)
    {
        if (is_string($commentaire))
        {
            $this->commentaire = $commentaire;
        }
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }
}