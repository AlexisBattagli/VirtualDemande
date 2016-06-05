<?php
//A installer obligatoirement : apt-get install  php5-mysqlnd*
//                              /etc/init.d/apache2 restart
// C'est le driver permettant d'avoir la fonction get_result()
/*
                            Script d'ajout d'un Utilisateur
//filter_input pour vérifier qu'il n'y a pas de "" (injection) et renvoi nul si elle est as possible ou erreur
//is_unique voir si il n'xiste pas encore
//guacamole=ajouter des permissions
//Ajout d'un utilisateur au deux bases
//Verifier connexion juste sur le site
//1 formulaire = 1 controlleur
//Methode post
//For=name=id
//Type=Submit
//Required= champ obligatoire
 */

//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Utilisateur.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Guacamole_User.php');
require_once('/var/www/VirtualDemande/model/class/Utilisateur.php');
require_once('/var/www/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once('/var/www/VirtualDemande/model/class/Guacamole_User.php');
require_once('/var/www/VirtualDemande/model/DAL/Guacamole_UserDAL.php');
require_once('/var/www/VirtualDemande/model/class/Guacamole_User_Permission.php');
require_once('/var/www/VirtualDemande/model/DAL/Guacamole_User_PermissionDAL.php');
require_once('/var/www/VirtualDemande/model/class/Groupe.php');
require_once('/var/www/VirtualDemande/model/DAL/GroupeDAL.php');
/*
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

//Vérification des méthodes de UtilisateurDAL : 
    //Vérification de GetNumberAvailableUsers() - OK
        //$id=UtilisateurDAL::GetNumberAvailableUsers();
        //echo "Nombre d'utilisateurs restants :".$id;

    //Vérification de isUnique() - OK

    //Vérification de findByDefault - OK 
        //$defautUtilisateur=UtilisateurDAL::findByDefault();
        //echo 'Utilisateur par défaut a pour ID:'.$defautUtilisateur->getId();
        //echo 'Utilisateur par défaut a pour Login:'.$defautUtilisateur->getLogin();

    //Vérification de findById - OK
        //$defautUtilisateur=UtilisateurDAL::findById(1);
        //echo 'Utilisateur par défaut a pour ID:'.$defautUtilisateur->getId();
        //echo 'Utilisateur par défaut a pour Login:'.$defautUtilisateur->getLogin(); 
    
    //Vérification de findAll - OK
        //$lesUtilisateurs=UtilisateurDAL::findAll();
        //$taille=count($lesUtilisateurs);
        //echo 'Nombre utilisateur :'.$taille;

    //Vérification de isFull() - OK
        //$statut=UtilisateurDAL::isFull("1");
        //if($statut==true)
        //{
            //echo "OK";
        //}

    //INSERT - OK

    //Suppression - OK
        //$validInsertUtilisateur = UtilisateurDAL::delete("2");

    //Test update - OK
        //$newUtilisateur->setId(12);
        //$validInsertUtilisateur = UtilisateurDAL::insertOnDuplicate($newUtilisateur);
 
    //Test findShareContener - OK
        //$lesUtilisateurs=UtilisateurDAL::findShareContener(1);
        //$taille=count($lesUtilisateurs);
        //echo 'Nombre utilisateur :'.$taille;

//Vérification des méthodes de Guacamole_UserDAL : 

        //INSERT - OK
        
        //Pour test update : OK
            //$newUserGuacamole->setUserId(158);
            //$validInsertUser = Guacamole_UserDAL::insertOnDuplicate($newUserGuacamole);

        //Pour test suppression - OK
            //$validSupprUser = Guacamole_UserDAL::delete(158);

        //Vérification de findByUsername($username) - OK

        //Vérification de findByDefault - OK
            //$defautUtilisateur=Guacamole_UserDAL::findByDefault();
            //echo 'Utilisateur par défaut a pour ID:'.$defautUtilisateur->getUserId();
            //echo 'Utilisateur par défaut a pour Login:'.$defautUtilisateur->getUsername();

        //Vérification de findById - OK
            //$defautUtilisateur=Guacamole_UserDAL::findById(3);
            //echo 'Utilisateur par défaut a pour ID:'.$defautUtilisateur->getUserId();
            //echo 'Utilisateur par défaut a pour Login:'.$defautUtilisateur->getUsername();

        //Vérification de findAll - 
            //$lesUsers=Guacamole_UserDAL::findAll();
            //$taille=count($lesUsers);
            //echo 'Nombre utilisateur :'.$taille;

//Vérification des méthodes de Guacamole_User_PermissionDAL : 
    //Vérification de Insert - OK

    //Vérification de Update - OK

    //Vérification de deleteAffectedUser - OK
        //$valid=Guacamole_User_PermissionDAL::deleteAffectedUser(14);

    //Vérification de deleteUser - OK
        //$valid=Guacamole_User_PermissionDAL::deleteUser(15);
        
    //Vérification de delete - OK
        //$valid=Guacamole_User_PermissionDAL::delete(14,15);

    //Vérification de findAll - OK
        //$lesUsers=Guacamole_User_PermissionDAL::findAll();
        //$taille=count($lesUsers);
        //echo 'Nombre de permission de utilisateur :'.$taille;
    
    //Vérification de findByUser - OK
        //$lesUsers=Guacamole_User_PermissionDAL::findByUser(1);
        //$taille=count($lesUsers);
        //echo 'Nombre de permission de utilisateur :'.$taille;

    //Vérification de findByAffectedUserId - OK
        //$lesUsers=Guacamole_User_PermissionDAL::findByAffectedUserId(3);
        //$taille=count($lesUsers);
        //echo 'Nombre de permission de utilisateur :'.$taille;
 */

//Vérification des méthodes de RoleAL : 
    //Vérification de findByDefault - OK 
        //$defautRole=RoleDAL::findByDefault();
        //echo 'Role par défaut a pour ID:'.$defautRole->getId();
        //echo 'Role par défaut a pour Login:'.$defautRole->getNomRole();

    //Vérification de findById - OK
        //$defautRole=RoleDAL::findById(2);
        //echo 'Role par défaut a pour ID:'.$defautRole->getId();
        //echo 'Role par défaut a pour Login:'.$defautRole->getNomRole();
    
    //Vérification de findAll - OK
        //$lesRoles=RoleDAL::findAll();
        //$taille=count($lesRoles);
        //echo 'Nombre role :'.$taille;

    //Vérification de findByRole - OK
        //$defautRole=RoleDAL::findByRole("titi");
        //echo 'Role par défaut a pour ID:'.$defautRole->getId();
        //echo 'Role par défaut a pour Login:'.$defautRole->getNomRole();

    //Vérification d'insertion - OK
        //$newRole=new Role();
        //$newRole->setNomRole("Taratoto");
        //$newRole->setDescription("Taratoto");
        //$validRole = RoleDAL::insertOnDuplicate($newRole);
        
    //Vérification de update - OK
        //$newRole=new Role();
        //$newRole->setId(4);
        //$newRole->setNomRole("Tara");
        //$newRole->setDescription("Taratto");
        //$validRole = RoleDAL::insertOnDuplicate($newRole);

    //Vérification de delete - OK
        //$validRole = RoleDAL::delete(4);

//Vérification des méthodes de GroupeDAL : 
    //Vérification de findByDefault - OK 
        //$defautGroupe=GroupeDAL::findByDefault();
        //echo 'Groupe par défaut a pour ID:'.$defautGroupe->getId();
        //echo 'Groupe par défaut a pour Nom:'.$defautGroupe->getNom();

    //Vérification de findById - OK
        //$defautGroupe=GroupeDAL::findById(2);
        //echo 'Groupe par défaut a pour ID:'.$defautGroupe->getId();
        //echo 'Groupe par défaut a pour Nom:'.$defautGroupe->getNom();
    
    //Vérification de findAll - OK
        //$lesGroupes=GroupeDAL::findAll();
        //$taille=count($lesGroupes);
        //echo 'Nombre role :'.$taille;

    //Vérification de findByNom - OK
        //$defautGroupe=GroupeDAL::findByNom("Group1");
        //echo 'Groupe par défaut a pour ID:'.$defautGroupe->getId();
        //echo 'Groupe par défaut a pour Nom:'.$defautGroupe->getNom();

    //Vérification d'insertion - OK
        //$newGroupe=new Groupe();
        //$newGroupe->setNom("Taratoto");
        //$newGroupe->setDateCreation("2010/05/02");
        //$newGroupe->setDescription("Taratoo");
        //$validGroupe = GroupeDAL::insertOnDuplicate($newGroupe);
        
    //Vérification de update - OK
        //$newGroupe=new Groupe();
        //$newGroupe->setId(14);
        //$newGroupe->setNom("Nom1oto");
        //$newGroupe->setDateCreation("0010/00/02");
        //$newGroupe->setDescription("DescripTaratoo");
        //$validGroupe = GroupeDAL::insertOnDuplicate($newGroupe);

    //Vérification de delete - OK
        //$validGroupe = GroupeDAL::delete(14);


