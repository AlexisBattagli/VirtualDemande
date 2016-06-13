<?php 

/*
 * Envoie l’id d’une VM e tl’id d’un groupe pour l’enlever du groupe 
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

if($validPage == "manage_groups.php")
{
    //=====Vérification de ce qui est renvoyé par le formulaire
    $validIdMachine = filter_input(INPUT_POST, 'idMachine', FILTER_SANITIZE_STRING);
    //echo "OK pour Id Machine : ".$validIdMachine;

    $validIdGroupe = filter_input(INPUT_POST, 'idGroupe', FILTER_SANITIZE_STRING);
    //echo "OK pour Id Groupe : ".$validIdGroupe;
    
    $validIdUser = $_COOKIE["user_id"];
    //echo "OK pour Id User : ".$validIdUser;
    $newLog->setLoginUtilisateur(UtilisateurDAL::findById($validIdUser)->getLogin());
    
    $newLog->setLevel("INFO");
    $newLog->setMsg("Initialisation de la suppression du partage de la machine (id:$validIdMachine) à un groupe (id:$validIdGroupe).");
    $newLog->setDateTime(date('Y/m/d G:i:s'));
    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);

    //Vérification si l'utilisateur fait partie du groupe
    if(!is_null(Groupe_has_MachineDAL::findByGM($validIdGroupe,$validIdMachine)))
    {
        //echo "Machine est bien dans le groupe
        $newLog->setLevel("INFO");
        $newLog->setMsg("Machine (id:$validIdMachine) est bien dans le groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);

        //Suppression de le la machine partagée par l'utilisateur dans le groupe
        $validDelete=Groupe_has_MachineDAL::delete($validIdGroupe, $validIdMachine);
        
        $newLog->setLevel("INFO");
        $newLog->setMsg("Suppression réussi du partage de la machine (id:$validIdMachine) à un groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        
        $message="ok";
    }
    else
    {
        $newLog->setLevel("ERROR");
        $newLog->setMsg("Machine (id:$validIdMachine) n'est pas dans le groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        //echo "Machine n'est pas dans le groupe";
        //Renvoie à la page précédante
            echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";
    

