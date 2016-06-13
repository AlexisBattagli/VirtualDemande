<?php

//Script de création d'un groupe

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');
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
        
    //Création d'un Utilisateur par défaut
    $newGroupe=new Groupe();

    //=====Vérification de ce qui est renvoyé par le formulaire
    $validNom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);

    if (!is_null($validNom))
    {
        $newGroupe->setNom($validNom);
        //echo "OK pour Nom : ".$newGroupe->getNom();
    }

    $validDescription = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    if (!is_null($validDescription))
    {
        $newGroupe->setDescription($validDescription);
        //echo "OK pour Description : ".$newGroupe->getDescription();
    }

    $newDateCreation=date("Y/m/d");
    $newGroupe->setDateCreation($newDateCreation);
    //echo "OK pour DateCréation:".$newGroupe->getDateCreation();
    
    $validIdUser = $_COOKIE["user_id"];
    //echo "OK pour Id User : ".$validIdUser;
    
    //$user=  UtilisateurDAL::findById($validUser);
    $newLog->setLoginUtilisateur(UtilisateurDAL::findById($validIdUser)->getLogin());
    
    $newLog->setLevel("INFO");
    $newLog->setMsg("Initialisation de la création d'un groupe.");
    $newLog->setDateTime(date('Y/m/d G:i:s'));
    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
    
    if (is_null(GroupeDAL::findByNom($validNom)))
    {
    //=====Insertion=====/ - OK
        $validInsertGroupe = GroupeDAL::insertOnDuplicate($newGroupe);

        if (!is_null($validInsertGroupe))
        {
            $newLog->setLevel("INFO");
            $newLog->setMsg("Ajout du groupe reussi dans la base DBVirtDemande ! (id: $validInsertGroupe )");
            $newLog->setDateTime(date('Y/m/d G:i:s'));
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            //echo "Ajout du groupe reussi dans la base DBVirtDemande ! (id:" . $validInsertGroupe . ")";
            
            $newUtilisateurHasGroupe=new Utilisateur_has_Groupe();
            $newUtilisateurHasGroupe->setGroupe($validInsertGroupe);
            $newUtilisateurHasGroupe->setUtilisateur($validIdUser);
            $validInsert=  Utilisateur_has_GroupeDAL::insertOnDuplicate($newUtilisateurHasGroupe);
            if(!is_null($validInsert))
            {
                $newLog->setLevel("INFO");
                $newLog->setMsg("Ajout reussi de l'utilisateur dans le groupe (id:$validInsertGroupe).");
                $newLog->setDateTime(date('Y/m/d G:i:s'));
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                $message="ok";
            }
            else
            {
                $newLog->setLevel("ERROR");
                $newLog->setMsg("Echec de l'Ajout de l'utilisateur dans le groupe (id:$validInsertGroupe).");
                $newLog->setDateTime(date('Y/m/d G:i:s'));
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            }
        }
        else
        {
            $newLog->setLevel("ERROR");
            $newLog->setMsg("Echec de l'Ajout du groupe dans la base DBVirtDemande !");
            $newLog->setDateTime(date('Y/m/d G:i:s'));
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            //echo "insert echec...";
        }
    }
    else
    {
        $newLog->setLevel("ERROR");
        $newLog->setMsg("Le groupe que vous voulez ajouter existe...");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
        //echo "Erreur, le groupe que vous voulez ajouter existe...";
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";
