<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/StockageDAL.php');

/* Pour test :
 * $data = array(true,false,true,false);
 */

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "updateRam")
{
    $data = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    $id=1;

    foreach ($data as $row)
    {
        //echo $row;
        $newStockage=StockageDAL::findById($id);
        while($newStockage==null)
        {
            $id=$id+1;
            $newStockage=StockageDAL::findById($id);
        }
        //echo "  NOM :".$newStockage->getValeur();
        $newStockage->setVisible($row);
        //echo "           Visible après :".$newStockage->getVisible();
        $validUpdate = StockageDAL::insertOnDuplicate($newStockage);
        $id=$id+1;
    }
    
    $message=true;
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";