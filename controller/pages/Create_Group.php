<?php

//Script de création d'un groupe

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');

//Création d'un Utilisateur par défaut
$newGroupe=new Groupe();

//=====Vérification de ce qui est renvoyé par le formulaire
$validNom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);

if ($validNom != null)
{
    $newGroupe->setNom($validNom);
    //echo "OK pour Nom : ".$newGroupe->getNom();
}

$validDescription = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

if ($validDescription != null)
{
    $newGroupe->setDescription($validDescription);
    //echo "OK pour Description : ".$newGroupe->getDescription();
}

$newDateCreation=date("Y/m/d");
$newGroupe->setDateCreation($newDateCreation);
//echo "OK pour DateCréation:".$newGroupe->getDateCreation();

if (GroupeDAL::findByNom($validNom) == null)
{
//=====Insertion=====/ - OK
    $validInsertGroupe = GroupeDAL::insertOnDuplicate($newGroupe);

    if ($validInsertGroupe != null)
    {
        echo "Ajout du groupe reussi dans la base DBVirtDemande ! (id:" . $validInsertGroupe . ")";
    }
    else
    {
        echo "insert echec...";
    }
    
    //Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";
}
else
{
    echo "Erreur, le groupe que vous voulez ajouter existe...";
}

