<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/StockageDAL.php');

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "forms_administration.php")
{
    //Récupération de la valeur passée
    $data = filter_input(INPUT_POST, 'idsStockage', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    if(!is_null($data))
    {
        //Passer à 0 les distribs pour qu'elles ne soient pas visibles
        $lesStockage= StockageDAL::findAll();

        foreach ($lesStockage as $row)
        {
            $newStockage=$row;
            $newStockage->setVisible(false);
            $validUpdate = StockageDAL::insertOnDuplicate($newStockage);
        }

        $id=1;

        foreach ($data as $row)
        {
            $newStockage=StockageDAL::findById($row);
            $newStockage->setVisible(true);
            $validUpdate = StockageDAL::insertOnDuplicate($newStockage);
        }

        $message="ok";
    }
    
}

//Renvoie à la page précédante
   echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";