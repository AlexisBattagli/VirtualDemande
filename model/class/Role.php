<?php

/**
 * Description of Role
 *
 * @author Alexis
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
     * role d'un Role dans la table Role
     * @var string
     */
    private $role;
    
    /*
     * description d'un Role dans la table Role
     * @var string
     */
    private $description;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     *

      /*
     * Constructeur par défaut de Role
     */

    public function Role(
    $id = -1, $role = "Aucun role pour ce role", $description = "Ce role n'a pas de description"
    )
    {
        $this->id = $id;
        $this->role = $role;
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
        $this->role = $dataSet['role'];
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
    public function setRole($role)
    {
        if (is_string($role))
        {
            $this->role = $role;
        }
    }

    public function getRole()
    {
        return $this->role;
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
