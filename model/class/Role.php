<?php

/**
 * Description of Role
 *
 * @author Alexis
 * @author Aurelie
 */
class Role {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    
    /*
     * Id d'un Role dans la table Role
     * @var int 
     */
    
    private $id;
    
    /*
     * nom_role d'un Role dans la table Role
     * @var string
     */
    private $nomRole;
    
    /*
     * description d'un Role dans la table Role
     * @var string
     */
    private $description;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */
    
    public function Role(
    $id = -1, $nomRole = "Aucun role pour ce role", $description = "Ce role n'a pas de description"
    )
    {
        $this->id = $id;
        $this->nomRole = $nomRole;
        $this->description = $description;
    }
    
    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->nomRole = $dataSet['nom_role'];
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
    
    //role
    public function setNomRole($nomRole)
    {
        if (is_string($nomRole))
        {
            $this->nomRole = $nomRole;
        }
    }

    public function getNomRole()
    {
        return $this->nomRole;
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
}
