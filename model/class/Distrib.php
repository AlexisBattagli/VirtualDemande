<?php

/**
 * Description of Distrib
 *
 * @author Alexis
 * @author Aurelie
 */
class Distrib {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */

    /*
     * Id d'un Distrib dans la table Distrib
     * @var int 
     */
    private $id;

    /*
     * Nom d'un Distrib dans la table Distrib
     * @var string 
     */
    private $nom;

    /*
     * Archi d'un Distrib dans la table Distrib
     * @var string
     */
    private $archi;

    /*
     * Version d'un Distrib dans la table Distrib
     * @var string
     */
    private $version;

    /*
     * IHM d'un Distrib dans la table Distrib
     * @var string
     */
    private $ihm;

    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     *

      /*
     * Constructeur par défaut de Distrib
     */

    public function Distrib(
    $id = -1, $nom = "Cette Distrib n'a pas de nom", $archi = "Aucun archi pour cette Distrib", $version = "Cette Distrib n'a pas de version", $ihm = "Cette Distrib n'a pas d'IHM"
    )
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->archi = $archi;
        $this->version = $version;
        $this->ihm = $ihm;
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
        $this->archi = $dataSet['archi'];
        $this->version = $dataSet['version'];
        $this->ihm = $dataSet['ihm'];
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

    //archi
    public function setArchi($archi)
    {
        if (is_string($archi))
        {
            $this->archi = $archi;
        }
    }

    public function getArchi()
    {
        return $this->archi;
    }

    //version
    public function setVersion($version)
    {
        if (is_string($version))
        {
            $this->version = $version;
        }
    }

    public function getVersion()
    {
        return $this->version;
    }

    //ihm
    public function setIhm($ihm)
    {
        if (is_string($ihm))
        {
            $this->ihm = $ihm;
        }
    }

    public function getIhm()
    {
        return $this->ihm;
    }

}
