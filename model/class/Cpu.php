<?php

/**
 * Description of Cpu
 *
 * @author Alexis
 * @author Aurelie
 */

class Cpu {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    
    /*
     * Id d'un Cpu dans la table Cpu
     * @var int 
     */
    
    private $id;
    
    /*
     * Valeur d'un Cpu dans la table Cpu
     * @var int 
     */
    
    private $nbCoeur;
    
    /*
     * Visible d'un Cpu dans la table Cpu
     * @var bool
     */
    private $visible;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */
    
    public function Cpu(
    $id = -1, $nbCoeur = -1, $visible=True
    )
    {
        $this->id = $id;
        $this->nbCoeur = $nbCoeur;
        $this->visible = $visible;
    }
    
    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->nbCoeur = $dataSet['nb_coeur'];
        $this->visible = $dataSet['visible'];
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
    
    //nb_coeur
    public function setNbCoeur($nbCoeur)
    {
        if (is_int($nbCoeur))
        {
            $this->nbCoeur = $nbCoeur;
        }
    }

    public function getNbCoeur()
    {
        return $this->nbCoeur;
    }
    
    //visible
    public function setVisible($visible)
    {
        if (is_bool($visible))
        {
            $this->visible = $visible;
        }
    }

    public function getVisible()
    {
        return $this->visible;
    }
}
