<?php

//import
// web
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Table_logDAL.php');

// guaca
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_Connection_ParameterDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_Connection_PermissionDAL.php');

//Script pour supprimer le container voulu

//Définition d'un objet Table_log pour faire des insert de log
$newLog = new Table_log();

//Définition du message renvoyé
$message="error";

//Checker de où il vient
$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "manage_containers.php")
{ 
    //=====Vérification de ce qui est renvoyé par le formulaire
    $validIdMachine = filter_input(INPUT_POST, 'idMachine', FILTER_SANITIZE_STRING);

    //Vérifier si le container existe
    $machine=MachineDAL::findById($validIdMachine);
    $validMachine=$machine->getId();

    if($machine != null)
    { 
        //Récupérer le login de l'utilisateur
        $loginUtilisateur=$machine->getUtilisateur()->getLogin();
        
        //Récupérer le nom de la machine
        $nomMachine=$machine->getNom();
        //echo "Le nom de la machine à supprimer est".$nomMachine;
        $newLog->setLevel("INFO");
        $newLog->setLoginUtilisateur($loginUtilisateur);
        $newLog->setMsg("Le nom de la machine à supprimer est".$nomMachine);
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
               
        //=====Appel Web Service pour remove le container sur le serveur de virt=====/
        try
        {
            $client = new SoapClient(null,
                array (
                        'uri' => 'http://virt-server/ws_lxc/RemoveContainerRequest',
                        'location' => 'http://virt-server/ws_lxc/remove_container_ws.php',
                        'trace' => 1,
                        'exceptions' => 0
                ));
            $result = $client->__call('removeContainer',
                array(
                        'nameContainer' => $nomMachine
                )); 
        }
        catch(SoapFault $f)
        {
            $newLog->setLevel("ERROR");
            $newLog->setLoginUtilisateur($loginUtilisateur);
            $newLog->setMsg("Erreur SOAP: ".$f);
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            exit();
        }

        $result=0;
        if ($result == "0")
        { 
            //Vérification s'il y a eu une insertion dans la base de données guacamole
            $connection=Guacamole_ConnectionDAL::findByNom($nomMachine); 
            
            if(!is_null($connection))
            {
                //Récupérer l'id de connexion
                $connectionId=$connection->getConnectionId();

                $newLog->setLevel("INFO");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Début de la suppression de la connection n°".$connectionId);
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                //echo "Début de la suppression de la connection n°$connectionId";

                //Supprimer les élements connection_parameter
                $validDeletePermission=Guacamole_Connection_ParameterDAL::deleteConnection($connectionId);
                $nbrePermission=count(Guacamole_Connection_ParameterDAL::findByConnection($connectionId));
                if($nbrePermission == 0)
                {
                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("L'ensemble des parametres de connection pour la connection n° $connectionId ont bien été supprimés.");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                    //echo "L'ensemble des parametres de connection pour la connection n° $connectionId ont bien été supprimés."; 
                }
                else
                {
                    $newLog->setLevel("ERROR");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("L'ensemble des parametres de connection pour la connection n° $connectionId n'ont pas bien été supprimés.");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                    //echo "L'ensemble des parametres de connection pour la connection n° $connectionId ont bien été supprimés."; 
                    exit();
                }

                //Supprimer les élements connection_permission
                $validDeleteParameter=Guacamole_Connection_PermissionDAL::deleteConnection($connectionId);
                $nbreParameter=count(Guacamole_Connection_PermissionDAL::findByConnection($connectionId));
                if($nbreParameter == 0)
                {
                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("L'ensemble des permissions de connection pour la connection n° $connectionId ont bien été supprimés.");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                    //echo "L'ensemble des permissions de connection pour la connection n° $connectionId ont bien été supprimés.";      
                }
                else
                {
                    $newLog->setLevel("ERROR");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("L'ensemble des permissions de connection pour la connection n° $connectionId n'ont pas bien été supprimés.");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                    //echo "L'ensemble des permissions de connection pour la connection n° $connectionId ont bien été supprimés.";   
                    exit();
                }

                //Supprimer le container dans la base guacamole
                $validDeleteConnection=Guacamole_ConnectionDAL::delete($connectionId);
                if(is_null(Guacamole_ConnectionDAL::findById($id)))
                {
                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("La connection n°$connectionId a bien été supprimé.");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                    //echo "La connection n°$connectionId a bien été supprimé."; 
                }
                else
                {
                    $newLog->setLevel("ERROR");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("La connection n°$connectionId n'a pas bien été supprimé.");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                    //echo "La connection n°$connectionId a bien été supprimé."; 
                    exit();
                }
            }
            else
            {
                $newLog->setLevel("WARN");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("La machine $nomMachine n'avait pas de connexion dans guacamole.");
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);                    
            }
            
            //Suppprimer les partages de cette machine
            $validDeletePartage=Groupe_has_MachineDAL::deleteMachine($validIdMachine);
            $nbreGroupeHasMachine=count(Groupe_has_MachineDAL::findByMachine($validIdMachine));
            if($nbreGroupeHasMachine == 0)
            {
                $newLog->setLevel("INFO");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("La machine $nomMachine a bien été enlever des groupe dans le(s)quel(s) elle était partagée.");
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                //echo "La machine $nomMachine a bien été enlever des groupe dans le(s)quel(s) elle était partagée."; 
            }
            else
            {
                $newLog->setLevel("ERROR");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("La machine $nomMachine n'a pas bien été enlever des groupe dans le(s)quel(s) elle était partagée.");
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                //echo "La machine $nomMachine a bien été enlever des groupe dans le(s)quel(s) elle était partagée."; 
                exit();
            }
            
            //Trouve l'user de la machine et décrémente de 1 son nombre de Container
            $owner = $machine->getUtilisateur();
            $owner->setNbVm($owner->getNbVm() - 1); 
            $validUser=UtilisateurDAL::insertOnDuplicate($owner);
            $newLog->setLevel("INFO");
            $newLog->setLoginUtilisateur($loginUtilisateur);
            $newLog->setMsg("Le quota de l'utilisateur $owner->getPseudo() est maintenant à $owner->getNbVm()");
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
            //echo "Le quota de l'utilisateur $owner->getPseudo() est maintenant à $owner->getNbVm()";
            
            //Supprimer le container dans la base DBVirtDemand
            $validDeleteMachine=MachineDAL::delete($validIdMachine);
            if(is_null(MachineDAL::findById($id)))
            {
                $newLog->setLevel("INFO");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("La machine $nomMachine d'id $validIdMachine appartenant à l'utilisateur $owner->getPseudo() a bien été supprimée !");
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                //echo "La machine $nomMachine d'id $validIdMachine appartenant à l'utilisateur $owner->getPseudo() a bien été supprimée !";
            }
            else
            {
                $newLog->setLevel("ERROR");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("La machine $nomMachine d'id $validIdMachine appartenant à l'utilisateur $owner->getPseudo() n'a pas bien été supprimée !");
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
                //echo "La machine $nomMachine d'id $validIdMachine appartenant à l'utilisateur $owner->getPseudo() a bien été supprimée !";
                exit();
            }
        
            $message="ok";
        }
        else
        {
            $newLog->setLevel("ERROR");
            $newLog->setLoginUtilisateur($loginUtilisateur);
            $newLog->setMsg("Erreur lors de la suppression du container nommé $nomMachine");
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);     
            //echo "Erreur lors de la suppression du container nommé ". $nomMachine; //TODO log
            exit();
        }
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";