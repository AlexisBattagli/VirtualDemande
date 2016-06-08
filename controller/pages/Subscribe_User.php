<?php

/*
 * Envoie de l’id d'un utilisateur et de l'id du groupe 
 * Script (controller) pour ajouter l’utilisateur d’un groupe
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Utilisateur_has_GroupeDAL.php');

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "subscribeUser")
{
    $newUtilisateurHasGroupe=new Utilisateur_has_Groupe();

    //=====Vérification de ce qui est renvoyé par le formulaire
    $validIdUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
    $newUtilisateurHasGroupe->setUtilisateur($validIdUser);
    //echo "OK pour Id User : ".$newUtilisateurHasGroupe->getUtilisateur()->getId();

    $validIdGroupe = filter_input(INPUT_POST, 'idGroupe', FILTER_SANITIZE_STRING);
    $newUtilisateurHasGroupe->setGroupe($validIdGroupe);
    //echo "OK pour Id Groupe : ".$newUtilisateurHasGroupe->getGroupe()->getId();

    //Vérification si l'utilisateur fait partie du groupe
    if(Utilisateur_has_GroupeDAL::findByGU($validIdGroupe,$validIdUser)==null)
    {
        //echo "Utilisateur n'est pas dans le groupe";

        //Ajout de l'utilisateur du groupe
        $validInsert=Utilisateur_has_GroupeDAL::insertOnDuplicate($newUtilisateurHasGroupe);
    }
    else
    {
        //echo "Utilisateur est deja dans le groupe";
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'?message='.$message. "' />";