<?php


/**
 * Description of Distrib_Alias
 *
 * @author Alexis
 */

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
    private $nom_complet;
    
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
}
