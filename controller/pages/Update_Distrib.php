<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/DistribDAL.php');

//Définition de l'url
  $urlCourante=$_SERVER["HTTP_REFERER"];
  $urlGet = explode("&",$urlCourante);
  $url=$urlGet[0];

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "forms_administration.php")
{
    //Récupération de la valeur passée
    $data = filter_input(INPUT_POST, 'idsDistrib', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    if(!is_null($data))
    {
        //Passer à 0 les distribs pour qu'elles ne soient pas visibles
        $lesDistrib= DistribDAL::findAll();

        foreach ($lesDistrib as $row)
        {
            $newDistrib=$row;
            $newDistrib->setVisible(false);
            $validUpdate = DistribDAL::insertOnDuplicate($newDistrib);
        }

        $id=1;

        foreach ($data as $row)
        {
            $newDistrib=DistribDAL::findById($row);
            $newDistrib->setVisible(true);
            $validUpdate = DistribDAL::insertOnDuplicate($newDistrib);
        }

        $message="ok";
    }
    
}

//Renvoie à la page précédante
   echo "<meta http-equiv='refresh' content='1; url=".$url.'&message='.$message. "' />";