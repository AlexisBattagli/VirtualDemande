<?php

/*
 * Envoie l’id d’une VM et l’id d’un groupe pour l’ajouter au groupe 
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Table_logDAL.php');

//Définition d'un objet Table_log pour faire des insert de log
$newLog = new Table_log();

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "manage_containers.php")
{
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

    $validIdUser = $_COOKIE["user_id"];
    //echo "OK pour Id User : ".$validIdUser;
    $newLog->setLoginUtilisateur(UtilisateurDAL::findById($validIdUser)->getLogin());
    
    $newLog->setLevel("INFO");
    $newLog->setMsg("Initialisation du partage de la machine (id:$validIdMachine) au groupe (id:$validIdGroupe).");
    $newLog->setDateTime(date('Y/m/d G:i:s'));
    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
    
    //Vérification si l'utilisateur fait partie du groupe
    if(is_null(Groupe_has_MachineDAL::findByGM($validIdGroupe,$validIdMachine)))
    {
        $newLog->setLevel("INFO");
        $newLog->setMsg("La machine (id:$validIdMachine) n'est pas dans le groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        //echo "Machine n'est pas dans le groupe";

        //Ajout de le la machine par l'utilisateur dans le groupe
        $validInsert=Groupe_has_MachineDAL::insertOnDuplicate($newGroupeHasMachine);
        
        $newLog->setLevel("INFO");
        $newLog->setMsg("Ajout réussi du partage de la machine (id:$validIdMachine) à un groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        
        $message=true;

    }
    else
    {
        $newLog->setLevel("WARN");
        $newLog->setMsg("La machine (id:$validIdMachine) est déjà dans le groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        //echo "Machine est deja dans le groupe";
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";

