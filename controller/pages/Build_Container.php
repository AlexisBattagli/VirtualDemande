<?php

//Script de création d'un Container pour un User donné

//import
// web
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
    $ihm = $dist->getIhm(); //utile pour l'insert en guaca (yes|no)
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
$newMachine->setDateCreation($newDateCreation);

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

//=====Analyse de ce qui est renvoyer par le ws (renvoyer par le SdA plus exactement)=====/
$code = substr($result, 0, 1); //Prend la valeur à la position 0 de la string $result et va jusqu'à atteindre une longeur de 1, soit la première lettre de cette string
echo "</br>Le code de retour vaut : ".$code; //TODO log
if ($code == "0") 
{ 
//=====Si Container créer======/
    echo "</br>Conteneur créer avec succès sur le serveur de Virt"; //TODO log
    $passwdRoot = substr($result, 2); //récupère le password root
    
    $container = MachineDAL::findById($validInsertMachine);
    $container->setDescription($container->getDescription() . " Mot de passe du compte root: ". $passwdRoot);
    $container->setEtat(0);
    
    //====Création de la connection======//
    $connectionContainer = new Guacamole_Connection();
    $connectionContainer->setConnectionName($validName); //DOnne le nom du container à la connection pour pouvoir l'identifier a la suppression !
    $connectionContainer->setMaxConnections(null);
    $connectionContainer->setMaxConnectionsPerUser(null);
    $connectionContainer->setParent(null);
    if($ihm=='yes')
    {
        $connectionContainer->setProtocol('vnc');
    }
    else if($ihm=='no')
    {
        $connectionContainer->setProtocol('ssh');
    }
    else
    {
        echo "Type d'IHM inconnu..."; //TODO log
    }
    $idConnectContainer = Guacamole_ConnectionDAL::insertOnDuplicate($connectionContainer);
    if($idConnectContainer != null)
    {//Si création de connection guaca ok
        //créer les parameter de la connection guaca
        $paramConnectContainer = new Guacamole_Connection_Parameter();
        
    }   
    else
    {
        echo "Erreur, la connection n'a pas bien était ajouter dans la DB de guaca..."; //TODO log
    }
}     
else if ($code == "1"){ //If failure pending create of contener
    echo "</br>Echec de création du conteneur... Contactez le support EVOLVE.";
    $container = MachineDAL::findById($validInsertMachine);
    $container->setEtat(1);
}
else 
{ //If fatal error unknow...
    echo "</br>Code retour inconnu, problème ... Contactez le support EVOLVE !";
    $container = MachineDAL::findById($validInsertMachine);
    $container->setEtat(1);
}

