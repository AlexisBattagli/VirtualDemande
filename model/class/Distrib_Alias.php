<?php

/**
 * Description of Distrib_Alias
 *
 * @author Alexis
 * @author Aurelie
 */
//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Distrib.php');

class Distrib_Alias {
    /*
      ==============================
      ========= ATTRIBUTS ==========
      ==============================
     */

    /*
     * Id d'un Distrib_Alias dans la table Distrib_Alias
     * @var int 
     */

    private $id;

    /*
     * Distrib de la distrib alias
     * @var Distrib
     */
    private $distrib;

    /*
     * nom_complet d'une distrib alias
     * @var string
     */
    private $nomComplet;

    /*
     * pseudo de la distri_alias
     * @var string
     */
    private $pseudo;

    /*
     * commentaire de la distrib alias
     * @vat string
     */
    private $commentaire;

    /*
      ==============================
      ======== CONSTRUCTEUR ========
      ==============================
     */

    public function Distrib_Alias(
    $id = -1, $distrib = null, $nomComplet = "Aucun nom complet pour cette distrib alias", $pseudo = "Aucun pseudo pour cette Distrib Alias", $commentaire = "Cette Distrib Alias n'a pas de commentaire"
    )
    {
        $this->id = $id;
        if (is_null($distrib))
        {
            $distrib = DistribDAL::findDefaultDistrib();
        }
        else
        {
            $this->distrib = $distrib;
        }
        $this->nomComplet = $nomComplet;
        $this->pseudo = $pseudo;
        $this->commentaire = $commentaire;
    }

    /*
      ==============================
      ========== METHODES ==========
      ==============================
     */

    public function hydrate($dataSet)
    {
        $this->id = $dataSet['id'];
        $this->distrib = $dataSet['Distrib_id'];
        $this->nomComplet = $dataSet['nom_complet'];
        $this->pseudo = $dataSet['pseudo'];
        $this->commentaire = $dataSet['commentaire'];
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

    //Ditrib
    public function setDistrib($distrib)
    {
        if (is_string($distrib))
        {
            $distrib = (int) $distrib;
            $this->distrib = DistribDAL::findById($distrib);
        }
        else if (is_int($distrib))
        {
            $this->distrib = DistribDAL::findById($distrib);
        }
        else if (is_a($distrib, "Distrib"))
        {
            $this->distrib = $distrib;
        }
    }

    public function getDistrib()
    {
        $distrib = null;
        if (is_int($this->distrib))
        {
            $distrib = DistribDAL::findById($this->distrib);
            $this->distrib = $distrib;
        }
        else if (is_a($this->distrib, "Distrib"))
        {
            $distrib = $this->distrib;
        }
        return $distrib;
    }

    //nom_complet
    public function setNomComplet($nomComplet)
    {
        if (is_string($nomComplet))
        {
            $this->nomComplet = $nomComplet;
        }
    }

    public function getNomComplet()
    {
        return $this->nomComplet;
    }

    //pseudo
    public function setPseudo($pseudo)
    {
        if (is_string($pseudo))
        {
            $this->pseudo = $pseudo;
        }
    }

    public function getPseudo()
    {
        $this->pseudo;
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
        $this->commentaire;
    }

}
