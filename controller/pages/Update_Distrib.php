<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/DistribDAL.php');

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
    $lesDistrib= DistribDAL::findAll();

    foreach ($lesDistrib as $row)
    {
        $newdistrib=$row;
        $newdistrib->setVisible(false);
        $validUpdate = DistribDAL::insertOnDuplicate($newdistrib);
    }
    
    //Récupération de la valeur passée
    $data = filter_input(INPUT_POST, 'idsDistrib', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    
    $id=1;
    
    foreach ($data as $row)
    {
        $newDistrib=DistribDAL::findById($row);
        $newDistrib->setVisible(true);
        $validUpdate = DistribDAL::insertOnDuplicate($newDistrib);
    }

    $message="ok";
}

//Renvoie à la page précédante
   echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";