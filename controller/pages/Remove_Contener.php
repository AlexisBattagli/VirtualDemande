<?php 

/*
 * Envoie l’id d’une VM e tl’id d’un groupe pour l’enlever du groupe 
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "removeContener")
{
    //=====Vérification de ce qui est renvoyé par le formulaire
    $validIdMachine = filter_input(INPUT_POST, 'idMachine', FILTER_SANITIZE_STRING);
    //echo "OK pour Id Machine : ".$validIdMachine;

    $validIdGroupe = filter_input(INPUT_POST, 'idGroupe', FILTER_SANITIZE_STRING);
    //echo "OK pour Id Groupe : ".$validIdGroupe;

    //Vérification si l'utilisateur fait partie du groupe
    if(Groupe_has_MachineDAL::findByGM($validIdGroupe,$validIdMachine)!=null)
    {
        //echo "Machine est bien dans le groupe";

        //Suppression de le la machine partagée par l'utilisateur dans le groupe
        $validDelete=Groupe_has_MachineDAL::delete($validIdGroupe, $validIdMachine);
    }
    else
    {
        //echo "Machine n'est pas dans le groupe";
    
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'?message='.$message. "' />";
    

