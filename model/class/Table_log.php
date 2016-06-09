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
     * msg d'un Table_log dans la table Table_log
     * @var string 
     */

    private $msg;
    
    /*
     * dateTime d'un Table_log dans la table Table_log
     * @var string 
     */

    private $dateTime;
    
    /*
     * level d'un Table_log dans la table Table_log
     * @var string 
     */

    private $level;
    
    /*
     * login_utilisateur d'un Table_log dans la table Table_log
     * @var string 
     */

    private $loginUtilisateur;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Table_log(
    $id = -1, $msg = "Aucune message", $level = null, $loginUtilisateur="Aucun utilisateur"
    )
    {
        $this->id = $id;
        $this->msg = $msg;
        $this->dateTime = date('Y/m/d G:i:s');
        $this->level = $level;
        $this->loginUtilisateur = $loginUtilisateur;
    }

    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->msg = $dataSet['msg'];
        $this->dateTime = $dataSet['date_time'];
        $this->level = $dataSet['level'];
        $this->loginUtilisateur = $dataSet['login_utilisateur'];
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
    
    //msg
    public function setMsg($msg)
    {
        if (is_string($msg))
        {
            $this->msg = $msg;
        }
    }

    public function getMsg()
    {
        return $this->msg;
    }
    
    //date_time
    public function setDateTime($dateTime)
    {
        if (is_string($dateTime))
        {
            $this->dateTime = $dateTime;
        }
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }
    
    //level
    public function setLevel($level)
    {
        if (is_string($level))
        {
            $this->level = $level;
        }
    }

    public function getLevel()
    {
        return $this->level;
    }
    
    //login_utilisateur
    public function setLoginUtilisateur($loginUtilisateur)
    {
        if (is_string($loginUtilisateur))
        {
            $this->loginUtilisateur = $loginUtilisateur;
        }
    }

    public function getLoginUtilisateur()
    {
        return $this->loginUtilisateur;
    }
}
