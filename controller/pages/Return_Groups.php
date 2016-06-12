<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "manage_containers.php")
{
    //=====Vérification de ce qui est renvoyé par le formulaire
    $validIdMachine = filter_input(INPUT_POST, 'idMachine', FILTER_SANITIZE_STRING);
    
    //Récupération de l'id de l'utilisateur
    $machine=  MachineDAL::findById($validIdMachine);
    $validIdUser= $machine->getUtilisateur()->getId();
        
    //Récupération des groupes de l'utilisateur où la machine n'est pas.
    $groupes=findLessMachine($validIdUser, $validIdMachine);
    
    //Envoi des groupes récupérés
    $jason=jason_encode($groupes);
    echo $jason;
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";

