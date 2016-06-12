<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/RamDAL.php');

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "forms_administration.php")
{
    //Récupération de la valeur passée
    $data = filter_input(INPUT_POST, 'idsRam', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    if(!is_null($data))
    {
        //Passer à 0 les distribs pour qu'elles ne soient pas visibles
        $lesRam= RamDAL::findAll();

        foreach ($lesRam as $row)
        {
            $newRam=$row;
            $newRam->setVisible(false);
            $validUpdate = RamDAL::insertOnDuplicate($newRam);
        }

        $id=1;

        foreach ($data as $row)
        {
            $newRam=RamDAL::findById($row);
            $newRam->setVisible(true);
            $validUpdate = RamDAL::insertOnDuplicate($newRam);
        }

        $message="ok";
    }
    
}

//Renvoie à la page précédante
   echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";