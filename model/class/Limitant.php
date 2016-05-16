<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Limitant
 *
 * @author Utilisateur
 */
class Limitant {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */
    
    /*
     * Id d'un Limitant dans la table Limitant
     * @var int 
     */
    
    private $id;
    
    /*
     * nbUserMax d'un Limitant dans la table Limitant
     * @var int 
     */
    
    private $nbUserMax;
    
    /*
     * nbVMUser d'un Limitant dans la table Limitant
     * @var int 
     */
    
    private $nbVMUser;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */
    
    public function Limitant(
    $id = -1, $nbUserMax = -1, $nbVMUser = -1
    )
    {
        $this->id = $id;
        $this->nbUserMax = $nbUserMax;
        $this->nbVMUser = $nbVMUser;
    }
    
    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->nbUserMax = $dataSet['nb_user_max'];
        $this->nbVMUser = $dataSet['nb_vm_user'];
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

    //nbUserMax
    public function setNbUserMax($nbUserMax)
    {
        if (is_int($nbUserMax))
        {
            $this->nbUserMax = $nbUserMax;
        }
    }

    public function getNbUserMax()
    {
        return $this->nbUserMax;
    }
     
    //id
    public function setNbVMUser($nbVMUser)
    {
        if (is_int($nbVMUser))
        {
            $this->nbVMUser = $nbVMUser;
        }
    }

    public function getNbVMUser()
    {
        return $this->nbVMUser;
    }
}
