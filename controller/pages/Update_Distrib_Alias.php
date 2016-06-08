<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Distrib_AliasDAL.php');

$data   = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$id=1;

$newDistribAlias=new Distrib_Alias();

foreach ($data as $row)
{
    $newDistribAlias=Distrib_AliasDAL::findById($id);
    $newDistribAlias->setVisible($row);
    $validUpdate = Distrib_AliasDAL::insertOnDuplicate($newDistribAlias);
    $id=$id+1;
}
echo "fin de DistribAlias";

//Renvoie à la page précédante
    //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";