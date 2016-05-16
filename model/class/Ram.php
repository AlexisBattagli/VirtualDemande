<?php

/**
 * Description of Ram
 *
 * @author Alexis
 * @author Aurelie
 */

class Ram {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    
    /*
     * Id d'un Ram dans la table Ram
     * @var int 
     */
    
    private $id;
    
    /*
     * Valeur d'un Ram dans la table Ram
     * @var int 
     */
    
    private $valeur;
    
    /*
     * Visible d'un Ram dans la table Ram
     * @var bool
     */
    private $visible;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */
    
    public function Ram(
    $id = -1, $valeur = -1, $visible=True
    )
    {
        $this->id = $id;
        $this->valeur = $valeur;
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
        $this->valeur = $dataSet['valeur'];
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
    
    //valeur
    public function setValeur($valeur)
    {
        if (is_int($valeur))
        {
            $this->valeur = $valeur;
        }
    }

    public function getValeur()
    {
        return $this->valeur;
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
