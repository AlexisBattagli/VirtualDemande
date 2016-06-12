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
    $data   = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    $id=1;
    
    foreach ($data as $row)
    {
        //echo $row;
        $newDistribAlias=Distrib_AliasDAL::findById($id);
        while($newDistribAlias==null)
        {
            $id=$id+1;
            $newDistribAlias=Distrib_AliasDAL::findById($id);
        }
        //echo "  NOM :".$newDistribAlias->getValeur();
        $newDistribAlias->setVisible($row);
        //echo "           Visible après :".$newDistribAlias->getVisible();
        $validUpdate = Distrib_AliasDAL::insertOnDuplicate($newDistribAlias);
        $id=$id+1;
    }
    
    $message=true;
}

//Renvoie à la page précédante
   echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";