<?php

//Script de création d'un Container pour un User donné
//import
// web
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Table_logDAL.php');

// guaca
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_Connection_ParameterDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Guacamole_Connection_PermissionDAL.php');

//Définition d'un objet Table_log pour faire des insert de log
$newLog = new Table_log();

//Définition d'un objet Table_log pour faire des insert de log
$newLog = new Table_log();

//Définition du message renvoyé
$message = "error";

//Checker de où il vient
$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if ($validPage == "manage_containers.php") 
{
    $machineClone = new Machine(); //Machine clonée

    //=====Vérification de ce qui est renvoyé par le formulaire
    $validNameMachineOrigine = filter_input(INPUT_POST, 'nameMachineOrigine', FILTER_SANITIZE_STRING); //sera utile pour insert et ws, nameContainer
    if ($validNameMachineOrigine != null) {
        $machineOrigine = MachineDAL::findByName($validNameMachineOrigine);//Machine à cloner
        MachineDAL::copy($machineOrigine, $machineClone); //copy en profondeur de la machine origine dans la machine clone    
        $machineClone->setDescription("Machine cloné à partir de la machine ".$machineOrigine->getNom()); //modofie la description

        $ihm = $machineClone->getDistribAlias()->getDistrib()->getIhm(); //récupère la valeur d'ihm pour savoir si la connection guacamol doit etre faite sur vns ou ssh
    }
    else{
        echo "Le nom de la machine origine n'a pas bien été récuèré.";
        exit();
    }

    $validIdUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
    if ($validIdUser != null) {
        $user = UtilisateurDAL::findById($validIdUser);
        $loginUtilisateur = $user->getLogin(); //création du champ login pour les logs
        $machineClone->setUtilisateur($user); //modifie l'utilisateur de la machine clone
    }
    else {
       echo "L'id de l'utilisateur n'a pas bien été récuèré.";
       exit(); 
    }

    $validNomMachineClone = filter_input(INPUT_POST, 'nomMachineClone', FILTER_SANITIZE_STRING);
    if ($validNomMachineClone != null) {
        $machineClone->setNom($validNomMachineClone); //modifie le noml de la machine clone
    }
    else {
       echo "Le nom de la machine clone n'a pas bien été récuèré.";
       exit(); 
    }

    //Modification de la date de création et d'expiration de la machine clonée
    $newDateCreation = date("Y-m-d");
    $machineClone->setDateCreation($newDateCreation);
    $date = date_create($newDateCreation);
    date_add($date, date_interval_create_from_date_string('1 year'));
    $dateExpiration = date_format($date, 'Y-m-d');
    $machineClone->setDateExpiration($dateExpiration);

    $machineClone->setEtat(2); //Modifie l'état de la machine clone à Creating


    if (UtilisateurDAL::isFull($validUserId) == false) {
        if (MachineDAL::findByName($validNomMachineClone) == null) {
    //=====Insertion de la Machine en base=====/ - OK
            $validInsertMachineClone = MachineDAL::insertOnDuplicate($machineClone);
            if ($validInsertMachineClone != null) {
                $newLog->setLevel("INFO");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Machine correctement ajoutée en base, d'id: " . $validInsertMachineClone);
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);

    //=====Incrémente le nombre de Container de l'utilisateur=====//
                $variable = $user->getNbVm() + 1;
                $user->setNbVm($variable);
                $validInsertNewNbCont = UtilisateurDAL::insertOnDuplicate($user);
                if($validInsertNewNbCont != null){
                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Mise a jour du quota, passe à ".$variable);
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                }
                else {
                    $newLog->setLevel("ERROR");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Echec de la mise a jour du quota");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    exit();
                }

    //=====Appel Web Service pour build le container sur le serveur de virt=====/
                try {
                    $client = new SoapClient(null, array(
                        'uri' => 'http://virt-server/ws_lxc/CloneContainerRequest',
                        'location' => 'http://virt-server/ws_lxc/clone_container_ws.php',
                        'trace' => 1,
                        'exceptions' => 0
                    ));
                    $result = $client->__call('cloneContainer', array(
                        'nameOriginContainer,' => $validNameMachineOrigine,
                        'nameCloneContainer' => $validNomMachineClone
                    ));
                        //echo "<pre>\n";
                        //echo "Request: \n" . htmlspecialchars($client->__getLastRequest()) . "\n";
                        //echo "Response: \n" . htmlspecialchars($client->__getLastResponse()) . "\n";
                        //echo "</pre>\n";
                } catch (SoapFault $f) {
                    $newLog->setLevel("ERROR");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Erreur SOAP: ".$f);
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    exit();
                }

    //=====Analyse de ce qui est renvoyer par le ws (renvoyer par le SdA plus exactement)=====/
                $code = substr($result, 0, 1); //Prend la valeur à la position 0 de la string $result et va jusqu'à atteindre une longeur de 1, soit la première lettre de cette string
                $newLog->setLevel("INFO");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Le code de retour de ws vaut : " . $code);
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);  

                if ($code == "0") {
                    $passwdRoot = substr($result, 1, 10); //récupère le password root de longeur 10 carac en partant du carac n°1
                    $addrIpContainer = substr($result, 11); //recupère l'addresse ip du container renvoyer par SdA
                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("L'adresse IP du conteneur " . $validNomMachineClone . " est: ".$addrIpContainer);
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);

                    $container = MachineDAL::findById($validInsertMachineClone);
                    $container->setDescription($container->getDescription() . " Mot de passe du compte root: " . $passwdRoot);
                    $container->setEtat(0);
                    $validUpdateDescClone = MachineDAL::insertOnDuplicate($container);
                    if($validUpdateDescClone != null){
                        $newLog->setLevel("INFO");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Mise à jour de la description du container ".$container->getNom()." en ajoutant le mot de passe root.");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog); 
                    } else {
                        $newLog->setLevel("ERROR");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Echec de la mise à jour de la description du container ".$container->getNom()." pour ajouter le mot de passe root.");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog); 
                        exit();
                    }

    //====Création de la connection======//
                    $connectionContainer = new Guacamole_Connection();
                    $connectionContainer->setConnectionName($validNomMachineClone); //DOnne le nom du container à la connection pour pouvoir l'identifier a la suppression !
                    $connectionContainer->setMaxConnections(null);
                    $connectionContainer->setMaxConnectionsPerUser(null);
                    $connectionContainer->setParent(null);            
                    if ($ihm == 'yes') {
                        $newLog->setLevel("INFO");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Connexion vnc pour le contenair " . $validNomMachineClone . ".");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        $connectionContainer->setProtocol('vnc');
                    } else if ($ihm == 'no') {
                        $newLog->setLevel("INFO");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Connexion vnc pour le contenair " . $validNomMachineClone . ".");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        $connectionContainer->setProtocol('ssh');
                    } else {
                        $newLog->setLevel("ERROR");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Type d'IHM inconnu... Si vous voyez ce message, posez-vous des questions...");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        exit();
                    }
                    $idConnectContainer = Guacamole_ConnectionDAL::insertOnDuplicate($connectionContainer);
                    if ($idConnectContainer != null) {//Si création de connection guaca ok
    //======créer les parameter de la connection guaca=====/
                        $paramConnectContainer = new Guacamole_Connection_Parameter();
                        $paramConnectContainer->setConnection($idConnectContainer);

                        //set le paramètre username
                        $paramConnectContainer->setParameterName("username");
                        $paramConnectContainer->setParameterValue("root");
                        $validInsertParamUsername = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                        if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre username = root de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée.");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre username = root de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }

                        //set le paramètre password
                        $paramConnectContainer->setParameterName("password");
                        $paramConnectContainer->setParameterValue($passwdRoot);
                        $validInsertParamPwd = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                        if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre password de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée.");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre password de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }

                        //set le paramètre hostname
                        $paramConnectContainer->setParameterName("hostname");
                        $paramConnectContainer->setParameterValue($addrIpContainer);
                        $validInsertParamHostname = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                        if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre hostname = " . $addrIpContainer . " de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée.");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre hostname = " . $addrIpContainer . " de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }

                        //set le paramètre port
                        $paramConnectContainer->setParameterName("port");
                        if ($ihm == 'yes') {
                            $paramConnectContainer->setParameterValue(5900);
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Port de connection vnc 5900, pour la connection n°" . $idConnectContainer);
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else if ($ihm == 'no') {
                            $paramConnectContainer->setParameterValue(22);
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Port de connection 22, pour la connection n°" . $idConnectContainer);
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Erreur, type d'ihm inconnu... Sérieux, comment ça a pu arriver ?!!");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }
                        $validInsertParamPort = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                        if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre port de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée.");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre port de la connection (connection n°" . $idConnectContainer . ") non ajoutée.");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }

                        //=====Créer les permission sur la connection pur l'user donné=====//
                        $guacUserId = Guacamole_UserDAL::findByUsername($loginUtilisateur);
                        $permConnectContainer = new Guacamole_Connection_Permission();
                        $permConnectContainer->setConnection($idConnectContainer);
                        $permConnectContainer->setUser($guacUserId);

                        //ajout la permission READ
                        $permConnectContainer->setPermission("READ");
                        $validInsertPermR = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission READ pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission READ pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }

                        //ajout la permission UPDATE
                        $permConnectContainer->setPermission("UPDATE");
                        $validInsertPermU = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission UPDATE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission UPDATE pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }

                        //ajout la permission DELETE
                        $permConnectContainer->setPermission("DELETE");
                        $validInsertPermD = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission DELETE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission DELETE pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }

                        //ajout la permission ADMINISTER
                        $permConnectContainer->setPermission("ADMINISTER");
                        $validInsertPermA = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission ADMINISTER pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission ADMINISTER pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                        }

                    } else {
                        $newLog->setLevel("ERROR");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Erreur, la connection n'a pas bien était ajouter dans la DB de guaca... Aller GoodLuck hein !");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        exit();   
                    }
                } else if ($code == "1") { //If failure pending create of contener
                    $newLog->setLevel("WARN");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Echec de création du conteneur... Contactez le support EVOLVE.");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    $container = MachineDAL::findById($validInsertMachine);
                    $container->setEtat(1);
                } else { //If fatal error unknow...
                    $newLog->setLevel("WARN");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Code retour inconnu, problème ... Contactez le support EVOLVE !");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    $container = MachineDAL::findById($validInsertMachine);
                    $container->setEtat(1);
                }
            } 
            else {
                $newLog->setLevel("ERROR");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Echec de l'insertion en base de la Machine ".$machineClone->getNom());
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                exit();
            }
        } 
        else {
            $newLog->setLevel("ERROR");
            $newLog->setLoginUtilisateur($loginUtilisateur);
            $newLog->setMsg("Un container existe déjà avec ce nom (" . $validNomMachineClone . ").");
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            exit();
        }
    } 
    else {
        $newLog->setLevel("WARN");
        $newLog->setLoginUtilisateur($loginUtilisateur);
        $newLog->setMsg("L'user " . $user->getLogin() . " a atteint son quota de Contenair");
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";