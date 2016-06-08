<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/RamDAL.php');

$data   = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$id=1;

$newRam=new Ram();

foreach ($data as $row)
{
    $newRam=RamDAL::findById($id);
    $newRam->setVisible($row);
    $validUpdate = RamDAL::insertOnDuplicate($newRam);
    $id=$id+1;
}
echo "fin de Ram";

//Renvoie à la page précédante
    //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";