<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/CpuDAL.php');

$data   = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$id=1;

$newCpu=new Cpu();

foreach ($data as $row)
{
    $newCpu=CpuDAL::findById($id);
    $newCpu->setVisible($row);
    $validUpdate = CpuDAL::insertOnDuplicate($newCpu);
    $id=$id+1;
}
echo "fin de Cpu";

//Renvoie à la page précédante
    //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";