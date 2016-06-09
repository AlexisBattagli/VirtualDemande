<?php

//import
// web
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');

// guaca
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_Connection_ParameterDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_Connection_PermissionDAL.php');

//Script pour supprimer le container voulu

//Définition du message renvoyé
$message="error";

//Checker de où il vient
$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "DeleteContainer")
{
    //=====Vérification de ce qui est renvoyé par le formulaire
    $validIdMachine = filter_input(INPUT_POST, 'idMachine', FILTER_SANITIZE_STRING);
    echo "OK pour Id Machine : ".$validIdMachine;

    //Vérifier si le container existe
    $machine=MachineDAL::findById($validIdMachine);

    if($machine != null)
    {
        //Récupérer le nom de la machine
        $nomMachine=$machine->getNom();
        //echo "Nom de la machine :".$NomMachine;
        
        //=====Appel Web Service pour remove le container sur le serveur de virt=====/
        try
        {
            $client = new SoapCLient(null,
                array (
                        'uri' => 'http://virt-server/RemoveContainerRequest',
                        'location' => 'http://virt-server/remove_container_ws.php',
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
            echo $f;
        }

        if ($result == "0")
        {
            //Récupérer l'id de connexion
            $connection=Guacamole_ConnectionDAL::findByNom($nomMachine);
            $connectionId=$connection->getConnectionId();
            //echo "Id de connexion de la machine :".$connectionId;

            //Supprimer les élements connection_parameter
            $validDeletePermission=Guacamole_Connection_ParameterDAL::deleteConnection($connectionId);
            //echo "Suppr Parameter"; 

            //Supprimer les élements connection_permission
            $validDeleteParameter=Guacamole_Connection_PermissionDAL::deleteConnection($connectionId);
            //echo "Suppr Permission";      

            //Supprimer le container dans la base guacamole
            $validDeleteConnection=Guacamole_ConnectionDAL::delete($connectionId);
            //echo "Suppr Connection";  

            //Suppprimer les partages de cette machine
            $validDeletePartage=Groupe_has_MachineDAL::deleteMachine($validIdMachine);
            //echo "Suppr Partage"; 
        
            //Trouve l'user de la machine et décrémente de 1 son nombre de Container
            $owner = $machine->getUtilisateur();
            $owner->setNbVm($owner->getNbVm() - 1); 

            //Supprimer le container dans la base DBVirtDemand
            $validDeleteMachine=MachineDAL::delete($validIdMachine);
            //echo "final";
        
            $message="ok";
        }
        else
        {
            echo "Erreur lors de la suppression du container nommé ". $nomMachine; //TODO log
        }
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";