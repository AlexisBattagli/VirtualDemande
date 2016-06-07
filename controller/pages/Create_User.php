<?php

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_UserDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_User_PermissionDAL.php');

//Création d'un Utilisateur par défaut
$newUtilisateur=new Utilisateur();

//=====Vérification de ce qui est renvoyé par le formulaire
$validLogin = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);

if ($validLogin != null)
{
    $newUtilisateur->setLogin($validLogin);
    //echo "OK pour Login : ".$newUtilisateur->getLogin();
}

$validNom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
if ($validNom != null)
{
    $newUtilisateur->setNom($validNom);
    //echo "OK pour Nom :".$newUtilisateur->getNom();
}

$validPrenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
if ($validPrenom != null)
{
    $newUtilisateur->setPrenom($validPrenom);
    //echo "OK pour Prenom :".$newUtilisateur->getPrenom();
}

$validEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
if ($validEmail != null)
{
    $newUtilisateur->setMail($validEmail);
    //echo "OK pour Mail :".$newUtilisateur->getMail();
}

$validDate = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
if ($validDate != null)
{
    $newUtilisateur->setDateNaissance($validDate);
    //echo "OK pour Date de Naissance:".$newUtilisateur->getDateNaissance();
}

$validPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
if ($validPassword != null)
{
    $newUtilisateur->setPassword($validPassword);
    //echo "OK pour Passwd:".$newUtilisateur->getPassword();
}

//nb_VM + dateCreation + roleId à générer en plus

$newUtilisateur->setNbVm("0");
//echo "OK pour NbVm:".$newUtilisateur->getNbVm();

$newUtilisateur->setDateCreation("0000/00/00");
//echo "OK pour DateCréation:".$newUtilisateur->getDateCreation();

$newUtilisateur->setRole("1");
//echo "OK pour Role_id:".$newUtilisateur->getRole()->getId();

//====Vérification de doublons==== - OK
if (UtilisateurDAL::isUnique($validLogin,$validEmail) == null)
{
//=====Insertion=====/ - OK
    $validInsertUtilisateur = UtilisateurDAL::insertOnDuplicate($newUtilisateur);

    if ($validInsertUtilisateur != null)
    {
        echo "Ajout de l'utilisateur reussi dans la base DBVirtDemande ! (id:" . $validInsertUtilisateur . ")";
        //Création d'un guacamole_user

        $newUserGuacamole=new Guacamole_User();

        //=====Vérification de ce qui est renvoyé par le formulaire
        $validUserName = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
        if ($validUserName != null)
        {
            $newUserGuacamole->setUserName($validUserName);
            //echo "OK pour Username : ".$newUserGuacamole->getUsername();
        }

        $validPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if ($validPassword != null)
        {
            $newUserGuacamole->setPasswordHash($validPassword);
            //echo "OK pour PasswdHash:".$newUserGuacamole->getPasswordHash();
        }

        //A rajouter : expired et disabled : mettre à 0

            $newUserGuacamole->setDisabled(0);
            //echo "OK pour Disabled:".$newUserGuacamole->getDisabled();

            $newUserGuacamole->setExpired(0);
            //echo "OK pour Expired:".$newUserGuacamole->getExpired();

            //Les $accessWindowStart, $accessWindowEnd doivent être à null sinon ils ne pourront pas accéder à n'importe quelle heure sur leurs machines
            //$validFrom=null, $validUntil=null, pareils

        echo "Valider";

        //====Vérification de doublons====
        if (Guacamole_UserDAL::findByUsername($validUserName) == null)
        {
        //=====Insertion=====/ - OK

            $validInsertUser = Guacamole_UserDAL::insertOnDuplicate($newUserGuacamole);

            if ($validInsertUser != null)
            {
                echo "Ajout de l'utilisateur reussi dans la base guacamole_db! (id:" . $validInsertUser . ")";
                //Paramètres des permissions de l'utilisateur
                $guacamoleUserPermission=new Guacamole_User_Permission();
                $guacamoleUserPermission->setUser($validInsertUser);
                $guacamoleUserPermission->setAffectedUser($validInsertUser);
                $guacamoleUserPermission->setPermission("READ");
                $valid=Guacamole_User_PermissionDAL::insertOnDuplicate($guacamoleUserPermission);
                $guacamoleUserPermission->setPermission("UPDATE");
                $valid=Guacamole_User_PermissionDAL::insertOnDuplicate($guacamoleUserPermission);
                $guacamoleUserPermission->setPermission("DELETE");
                $valid=Guacamole_User_PermissionDAL::insertOnDuplicate($guacamoleUserPermission);
                $guacamoleUserPermission->setPermission("ADMINISTER");
                $valid=Guacamole_User_PermissionDAL::insertOnDuplicate($guacamoleUserPermission);
            }
            else
            {
                echo "insert echec...";
            }

            //Renvoie à la page précédante
            //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";
        }
        else
        {
            echo "Erreur, l'utilisateur que vous voulez ajouter existe déjà...";
        }    
    }
    else
    {
        echo "insert echec...";
    }
    
    //Renvoie à la page précédante
    //echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"]. "' />";
}
else
{
    echo "Erreur, l'utilisateur que vous voulez ajouter existe...";
}
