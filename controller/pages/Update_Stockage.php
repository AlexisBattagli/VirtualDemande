<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/StockageDAL.php');

$data   = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$id=1;

$newStockage=new Stockage();

foreach ($data as $row)
{
    $newStockage=StockageDAL::findById($id);
    $newStockage->setVisible($row);
    $validUpdate = StockageDAL::insertOnDuplicate($newStockage);
    $id=$id+1;
}
echo "fin de Stockage";

//Renvoie à la page précédante
    //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";