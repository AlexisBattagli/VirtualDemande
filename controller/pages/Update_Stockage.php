<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/StockageDAL.php');

$data = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

/* Pour test :
 * $data = array(true,false,true,false);
 */
 
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

//Renvoie à la page précédante
    //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";