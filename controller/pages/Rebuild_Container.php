<?php

//Script de rebuild un container

//import
// web
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Table_logDAL.php');

// guaca
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_Connection_ParameterDAL.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_Connection_PermissionDAL.php');

//Définition d'un objet Table_log pour faire des insert de log
$newLog = new Table_log();

$machineBuildPresent=false;

//=====Vérification de ce qui est renvoyé par le formulaire
$validIdMachine = filter_input(INPUT_POST, 'idMachine', FILTER_SANITIZE_STRING);
if(!is_null($validIdMachine))
{
    $machine = MachineDAL::findById($validIdMachine);
}


$validIdUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
if(!is_null($validIdUser))
{
    $user = UtilisateurDAL::findById($validIdUser);
    
//=====Décrémente son quota de container======/
    $user->setNbVm($user->getNbVm()-1);
    UtilisateurDAL::insertOnDuplicate($user);
}

//=====Vérif présence de machinebuild en base=====/
if(!is_null(MachineDAL::findByName($machine->getNom()."_build"))){
    $machineBuildPresent = true;
}

if(!$machineBuildPresent){
//=====Si machinebuild n'existe pas encore alors crée le======/
    $machineBuild = new Machine();
    MachineDAL::copy($machine, $machineBuild);
    $machineBuild->setNom($machine->getNom()."_build");
    
    //====Prépare la quete POST pour créer ce new Container=====/
    $url_delete="http://web-server/VirtualDemande/controller/pages/Delete_Container.php"; 

    // Tableau associatif $postFields des variables qui seront envoyées par POST au serveur
    $postfields_delete = array(
        'page' => 'Rebuild_Container.php',
        'nameContainer' => $machineBuild->getNom(),
        'descriptionContainer' => $machineBuild->getDescription(),
        'dist' => $machineBuild->getDistribAlias()->getId(),
        'ram' => $machineBuild->getRam()->getId(),
        'stock' => $machineBuild->getStockage()->getId(),
        'cpu' => $machineBuild->getCpu()->getId(),
        'user' => $user->getId()
    );

    // Tableau contenant les options de téléchargement
    $options=array(
          CURLOPT_URL            => $url_delete,       // Url cible
          CURLOPT_RETURNTRANSFER => true,       // Retourner le contenu téléchargé dans une chaine (au lieu de l'afficher directement)
          CURLOPT_HEADER         => false,      // Ne pas inclure l'entête de réponse du serveur dans la chaine retournée
          CURLOPT_FAILONERROR    => true,       // Gestion des codes d'erreur HTTP supérieurs ou égaux à 400
          CURLOPT_POST           => true,       // Effectuer une requête de type POST
          CURLOPT_POSTFIELDS     => $postFields_delete // Le tableau associatif contenant les variables envoyées par POST au serveur
    );

    // Création d'un nouvelle ressource cURL
    $CURL=curl_init();
    // Erreur suffisante pour justifier un die()
    if(empty($CURL))
    {
        die("ERREUR curl_init : Il semble que cURL ne soit pas disponible.");
    }
          // Configuration des options de téléchargement
          curl_setopt_array($CURL,$options);

          // Exécution de la requête
          $content=curl_exec($CURL);            // Le contenu retourner est enregistré dans la variable $content

          if(curl_errno($CURL)){
                // Le message d'erreur correspondant est affiché
                echo "ERREUR curl_exec : ".curl_error($CURL);
          }

    // Fermeture de la session cURL
    curl_close($CURL);   

}else{
    echo "machine build déjà en base";
    echo "il faut supprimer l'ancienne version";
}


?>