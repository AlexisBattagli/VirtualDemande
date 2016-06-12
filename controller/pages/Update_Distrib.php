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
    $data   = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

    $id=1;

    foreach ($data as $row)
    {
        //echo $row;
        $newDistrib=DistribDAL::findById($id);
        while($newDistrib==null)
        {
            $id=$id+1;
            $newDistrib=DistribDAL::findById($id);
        }
        //echo "  NOM :".$newDistrib->getValeur();
        $newDistrib->setVisible($row);
        //echo "           Visible après :".$newDistrib->getVisible();
        $validUpdate = DistribDAL::insertOnDuplicate($newDistrib);
        $id=$id+1;
    }
    
    $message="ok";
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";