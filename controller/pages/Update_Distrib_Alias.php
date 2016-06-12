<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Distrib_AliasDAL.php');

/* Pour test :
 * $data = array(true,false,true,false); 
 */

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "forms_administration.php")
{
    
    //Passer à 0 les distribs pour qu'elles ne soient pas visibles
    $lesDistribAlias= Distrib_AliasDAL::findAll();

    foreach ($lesDistribAlias as $row)
    {
        $newdistribAlias=$row;
        $newdistribAlias->setVisible(false);
        $validUpdate = Distrib_AliasDAL::insertOnDuplicate($newdistribAlias);
    }
    
    //Récupération de la valeur passée
    $data = filter_input(INPUT_POST, 'idsDistribAlias', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    
    $id=1;
    
    foreach ($data as $row)
    {
        $newDistribAlias=Distrib_AliasDAL::findById($row);
        $newDistribAlias->setVisible(true);
        $validUpdate = Distrib_AliasDAL::insertOnDuplicate($newDistribAlias);
    }

    $message="ok";
}

//Renvoie à la page précédante
   echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";