<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/CpuDAL.php');

$data = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

/* Pour test :
 * $data = array(true,false,true,false); 
 */

$id=1;

foreach ($data as $row)
{
    //echo $row;
    $newCpu=CpuDAL::findById($id);
    while($newCpu==null)
    {
        $id=$id+1;
        $newCpu=CpuDAL::findById($id);
    }
    //echo "  NOM :".$newCpu->getValeur();
    $newCpu->setVisible($row);
    //echo "           Visible après :".$newCpu->getVisible();
    $validUpdate = CpuDAL::insertOnDuplicate($newCpu);
    $id=$id+1;
}

//Renvoie à la page précédante
    //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";