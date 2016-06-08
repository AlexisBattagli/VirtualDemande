<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/DistribDAL.php');

$data   = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$id=1;

$newDistrib=new Distrib();

foreach ($data as $row)
{
    $newDistrib=DistribDAL::findById($id);
    $newDistrib->setVisible($row);
    $validUpdate = DistribDAL::insertOnDuplicate($newDistrib);
    $id=$id+1;
}
echo "fin de Distrib";

//Renvoie à la page précédante
    //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";