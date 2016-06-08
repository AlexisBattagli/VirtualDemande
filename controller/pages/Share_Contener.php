<?php

/*
 * Envoie l’id d’une VM et l’id d’un groupe pour l’ajouter au groupe 
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');

$newGroupeHasMachine=new Groupe_has_Machine();

//=====Vérification de ce qui est renvoyé par le formulaire
$validIdMachine = filter_input(INPUT_POST, 'idMachine', FILTER_SANITIZE_STRING);
$newGroupeHasMachine->setMachine($validIdMachine);
//echo "OK pour Id Machine : ".$newGroupeHasMachine->getMachine()->getId();

$validIdGroupe = filter_input(INPUT_POST, 'idGroupe', FILTER_SANITIZE_STRING);
$newGroupeHasMachine->setGroupe($validIdGroupe);
//echo "OK pour Id Groupe : ".$newGroupeHasMachine->getGroupe()->getId();

$validIdComment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
$newGroupeHasMachine->setCommentaire($validIdComment);
//echo "OK pour Commentaire : ".$newGroupeHasMachine->getCommentaire;

//Vérification si l'utilisateur fait partie du groupe
if(Groupe_has_MachineDAL::findByGM($validIdGroupe,$validIdMachine)==null)
{
    //echo "Machine n'est pas dans le groupe";
    
    //Suppression de le la machine partagée par l'utilisateur dans le groupe
    $validDelete=Groupe_has_MachineDAL::insertOnDuplicate($newGroupeHasMachine);

}
else
{
    //echo "Machine est deja dans le groupe";
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";

