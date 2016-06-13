<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');

//Définition du message renvoyé
$message = "error";

//Checker de où il vient
//=====Vérification de ce qui est renvoyé par le formulaire
$validIdMachine = filter_input(INPUT_POST, 'idMachine', FILTER_SANITIZE_STRING);
$machine = MachineDAL::findById($validIdMachine);

//Récupération de l'id de l'utilisateur
$validIdUser = $machine->getUtilisateur()->getId();

//Récupération des groupes de l'utilisateur où la machine n'est pas.
$groupes = GroupeDAL::findLessMachine($validIdUser, $validIdMachine);

$groupesJson = [];
foreach ($groupes as $group) {
    $groupesJson[$group->getId()]['id'] = $group->getId();
    $groupesJson[$group->getId()]['nom'] = $group->getNom();
    $groupesJson[$group->getId()]['date_creation'] = $group->getDateCreation();
    $groupesJson[$group->getId()]['description'] = $group->getDescription();
}
//Envoi des groupes récupérés
$json = json_encode($groupesJson);
echo $json;



