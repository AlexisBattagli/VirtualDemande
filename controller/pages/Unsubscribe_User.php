<?php

/*
 * Envoie de l’id d'un utilisateur et de l'id du groupe 
 * Script (controller) pour supprimer l’utilisateur d’un groupe
 * (=> suppression de toute ces VM partagé dans ce groupe)
 */

//Définition de l'url
  $urlCourante=$_SERVER["HTTP_REFERER"];
  $urlGet = explode("&",$urlCourante);
  $url=$urlGet[0];

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Utilisateur_has_GroupeDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
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
    $validIdUser = $_COOKIE["user_id"]; 
    //echo "OK pour Id User : ".$validIdUser;
    $newLog->setLoginUtilisateur(UtilisateurDAL::findById($validIdUser)->getLogin());

    $validIdGroupe = filter_input(INPUT_POST, 'idGroupe', FILTER_SANITIZE_STRING);
    //echo "OK pour Id Groupe : ".$validIdGroupe;

    $newLog->setLevel("INFO");
    $newLog->setMsg("Initialisation de la suppression de l'utilisateur (id:$validIdUser) au groupe (id:$validIdGroupe).");
    $newLog->setDateTime(date('Y/m/d G:i:s'));
    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);

    //Vérification si l'utilisateur fait partie du groupe
    if(!is_null(Utilisateur_has_GroupeDAL::findByGU($validIdGroupe,$validIdUser)))
    {
        $newLog->setLevel("INFO");
        $newLog->setMsg("Utilisateur (id:$validIdUser) est bien dans le groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        //echo "Utilisateur est bien dans le groupe";

        //Suppression de l'utilisateur du groupe
        $validDelete=Utilisateur_has_GroupeDAL::delete($validIdGroupe,$validIdUser);

        //Vérification si l'uitilisateur avait des machines partagés dans ce groupe
        $groupeHasMachines=Groupe_has_MachineDAL::findByShareByUserByGroupe($validIdUser,$validIdGroupe);
        if(!is_null($groupeHasMachines))
        {
            $newLog->setLevel("INFO");
            $newLog->setMsg("Utilisateur (id:$validIdUser) a des machines dans le groupe (id:$validIdGroupe).");
            $newLog->setDateTime(date('Y/m/d G:i:s'));
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            //echo "Utilisateur a des machines dans le groupe";
            //Suppression de la liste des machines de l'utilisateur dans ce groupe
            foreach ($groupeHasMachines as $row)
            {
                $groupeId=$row->getGroupe()->getId();
                //echo $groupeId;
                $machineId=$row->getMachine()->getId();
                //echo $machineId;
                $validDelete=Groupe_has_MachineDAL::delete($groupeId, $machineId);
            }
            $newLog->setLevel("INFO");
            $newLog->setMsg("Suppressions réussies des machines de l'Utilisateur (id:$validIdUser) qui sont dans le groupe (id:$validIdGroupe).");
            $newLog->setDateTime(date('Y/m/d G:i:s'));
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        }
        else 
        {
            $newLog->setLevel("WARN");
            $newLog->setMsg("Utilisateur (id:$validIdUser) n'a pas de machines dans le groupe (id:$validIdGroupe).");
            $newLog->setDateTime(date('Y/m/d G:i:s'));
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            //echo "Utilisateur n'a pas des machines dans le groupe";
        }
        
        $message="ok";
    }
    else
    {
        $newLog->setLevel("WARN");
        $newLog->setMsg("Utilisateur (id:$validIdUser) n'est pas dans le groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        //echo "Utilisateur n'est pas dans le groupe";
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$url.'&message='.$message. "' />";