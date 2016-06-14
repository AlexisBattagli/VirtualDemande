<?php
session_start();
/*
 * Envoie de l’id d'un utilisateur et de l'id du groupe 
 * Script (controller) pour ajouter l’utilisateur d’un groupe
 */

//Définition de l'url
  $urlCourante=$_SERVER["HTTP_REFERER"];
  $urlGet = explode("&",$urlCourante);
  $url=$urlGet[0];

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Utilisateur_has_GroupeDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Table_logDAL.php');

//Définition d'un objet Table_log pour faire des insert de log
$newLog = new Table_log();

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "manage_groups.php")
{ 
    $newUtilisateurHasGroupe=new Utilisateur_has_Groupe();

    //=====Vérification de ce qui est renvoyé par le formulaire
    $validIdUser = $_SESSION["user_id"];
    $newUtilisateurHasGroupe->setUtilisateur($validIdUser);
    // echo "OK pour Id User : ".$newUtilisateurHasGroupe->getUtilisateur()->getId();
    $newLog->setLoginUtilisateur(UtilisateurDAL::findById($validIdUser)->getLogin());
    
    $validIdGroupe = filter_input(INPUT_POST, 'idGroupe', FILTER_SANITIZE_STRING);
    $newUtilisateurHasGroupe->setGroupe($validIdGroupe);
    //echo "OK pour Id Groupe : ".$newUtilisateurHasGroupe->getGroupe()->getId();

    $newLog->setLevel("INFO");
    $newLog->setMsg("Initialisation de l'inscription de l'utilisateur (id:$validIdUser) au groupe (id:$validIdGroupe).");
    $newLog->setDateTime(date('Y/m/d G:i:s'));
    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
    
    //Vérification si l'utilisateur fait partie du groupe
    if(is_null(Utilisateur_has_GroupeDAL::findByGU($validIdGroupe,$validIdUser)))
    {
        $newLog->setLevel("INFO");
        $newLog->setMsg("Utilisateur (id:$validIdUser) n'est pas dans le groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        //echo "Utilisateur n'est pas dans le groupe";

        //Ajout de l'utilisateur du groupe
        $validInsert=Utilisateur_has_GroupeDAL::insertOnDuplicate($newUtilisateurHasGroupe);
        
        $newLog->setLevel("INFO");
        $newLog->setMsg("Ajout réussi de l'utilisateur (id:$validIdUser) au groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        
        $message="ok";
    }
    else
    {
        $newLog->setLevel("WARN");
        $newLog->setMsg("Utilisateur (id:$validIdUser) est deja dans le groupe (id:$validIdGroupe).");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        //echo "Utilisateur est deja dans le groupe";
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$url.'&message='.$message. "' />";