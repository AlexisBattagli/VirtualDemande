<?php

//Script de création d'un Container pour un User donné

//import
// web
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur.php');

// guaca
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_Connection.php');

$newMachine = new Machine();

//=====Vérification de ce qui est renvoyé par le formulaire
$validName = filter_input(INPUT_POST, 'nameContainer', FILTER_SANITIZE_STRING);//sera utile pour insert et ws, nameContainer
if ($validName != null)
{
    $newMachine->setNom($validName);
}

$validDesc = filter_input(INPUT_POST, 'descriptionContainer', FILTER_SANITIZE_STRING);//utile pour insert
if ($validDesc != null)
{
    $newMachine->setDescription($validDesc);
}

$validDistAliasId = filter_input(INPUT_POST, 'dist', FILTER_SANITIZE_STRING);
if ($validDistAliasId != null)
{
    $distAlias = Distrib_AliasDAL::findById($validDistAliasId);//sera utile pour l'insertt en base
    $newMachine->setDistribAlias($distAlias);
    
    $dist = $distAlias->getDistrib(); 
    $distribName = $dist->getNom(); //utile pour le ws, distrib
    $archi = $dist->getArchi(); //utile pour le ws, archi
    $version = $dist->getVersion(); //utile pour le ws, release
    
}

$validRamId = filter_input(INPUT_POST, 'ram', FILTER_SANITIZE_STRING);
if ($validRamId != null)
{
    $ram = RamDAL::findById($validRamId);//sera utile pour l'insertt en base
    $newMachine->setRam($ram);
    $valueRam = $ram->getValeur();  //sera utile pour le ws, ram
}

$validStockId = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_STRING);
if ($validStockId != null)
{
    $stock = StockageDAL::findById($validStockId);//sera utile pour l'insertt en base
    $newMachine->setStockage($stock);
    $valueStock = $stock->getValeur();//sera utile pour le ws, stockage
}

$validCpuId = filter_input(INPUT_POST, 'cpu', FILTER_SANITIZE_STRING);
if ($validCpuId != null)
{
    $cpu = CpuDAL::findById($validCpuId);//sera utile pour l'insertt en base
    $newMachine->setCpu($cpu);
    $valueCpu = $cpu->getNbCoeur();//sera utile pour le ws, cpu
}

$validUserId = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING); //sera utile pour l'insert
if ($validUserId != null)
{
    $user = UtilisateurDAL::findById($validUserId); //sert à l'insert
    $newMachine->setUtilisateur($user);
}

$newDateCreation=date("Y/m/d");
$newGroupe->setDateCreation($newDateCreation);

$newMachine->setEtat(2);


if(MachineDAL::findByName($validName) == null)
{
//=====Insertion de la Machine en base=====/ - OK
    $validInsertMachine = MachineDAL::insertOnDuplicate($newMachine);
    if ($validInsertMachine != null)
    {
        echo "Machine correctement ajouter en base, d'id: ". $validInsertMachine; // TODO log
    }
    else
    {
        echo "Echec de l'insertion en base de la Machine"; // TODO log
    }
}
 else
 {
     echo "Un container existe déjà avec ce nom..."; // TODO log
 }
 
//=====Appel Web Service pour build le container sur le serveur de virt=====/
try
{
        $client = new SoapCLient(null,
                array (
                        'uri' => 'http://virt-server/BuildContainerRequest',
                        'location' => 'http://virt-server/build_container_ws.php',
                        'trace' => 1,
                        'exceptions' => 0
                ));
        $result = $client->__call('buildContainer',
                array(
                        'nameContainer' => $validName,
                        'archi' => $archi,
                        'distrib' => $distribName,
                        'release' => $version, 
                        'ram' => $valueRam,
                        'cpu' => $valueCpu,
                        'stockage' => $valueStock
                ));
}
catch(SoapFault $f)
{
        echo $f;
}
