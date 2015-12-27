<?php

/**
 * Description of Groupe
 *
 * @author Alexis
 */
class Groupe {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    /*
     * Id d'un Groupe dans la table Groupe
     * @var int 
     */

    private $id;
    
    /*
     * Nom d'un Groupe dans la table Groupe
     * @var string
     */
    private $nom;
    
    /*
     * date_creation d'un Groupe dans la table Groupe
     * @var datetime
     */
    private $dateCreation;
    
    /*
     * description d'un Groupe dans la table Groupe
     * @vat string
     */
    private $description;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     *

      /*
     * Constructeur par défaut de Groupe
     */

    public function Groupe(
    $id = -1, $nom = "Aucun nom pour ce groupe", $dateCreation="0000-00-00",$description = "Ce Groupe n'a pas de description"
    )
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->dateCreation = $dateCreation;
        $this->description = $description;
    }

    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    protected function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->nom = $dataSet['nom'];
        $this->dateCreation = $dataSet['date_creation'];
        $this->description = $dataSet['description'];
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
    
    //dateCreation
    public function setDateCreation($dateCreation)
    {
        if (is_a($dateCreation, "DateTime"))
        {
            $this->dateCreation = $dateCreation;
        }
    }

    public function getDateCreation()
    {
        return $this->dateCreation;
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

}
