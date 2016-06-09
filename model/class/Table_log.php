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
     * action d'un Table_log dans la table Table_log
     * @var string 
     */

    private $msg;
    
    /*
     * dateHeure d'un Table_log dans la table Table_log
     * @var string 
     */

    private $dateTime;
    
    /*
     * action d'un Table_log dans la table Table_log
     * @var string 
     */

    private $level;
    
    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Table_log(
    $id = -1, $msg = "Aucune message", $level = null
    )
    {
        $this->id = $id;
        $this->msg = $msg;
        $this->dateTime = date('Y/m/d G:i:s');
        $this->level = $level;
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
    
    //dateHeure
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
}
