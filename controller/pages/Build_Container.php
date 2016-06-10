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

//Définition du message renvoyé
$message = "error";

//Checker de où il vient
$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if ($validPage == "manage_containers.php") {
    $newMachine = new Machine();

    //=====Vérification de ce qui est renvoyé par le formulaire
    $validName = filter_input(INPUT_POST, 'nameContainer', FILTER_SANITIZE_STRING); //sera utile pour insert et ws, nameContainer
    if ($validName != null) {
        $newMachine->setNom($validName);
    }

    $validDesc = filter_input(INPUT_POST, 'descriptionContainer', FILTER_SANITIZE_STRING); //utile pour insert
    if ($validDesc != null) {
        $newMachine->setDescription($validDesc);
    }

    $validDistAliasId = filter_input(INPUT_POST, 'dist', FILTER_SANITIZE_STRING);
    if ($validDistAliasId != null) {
        $distAlias = Distrib_AliasDAL::findById($validDistAliasId); //sera utile pour l'insertt en base
        $newMachine->setDistribAlias($distAlias);

        $dist = $distAlias->getDistrib();
        $distribName = $dist->getNom(); //utile pour le ws, distrib
        $archi = $dist->getArchi(); //utile pour le ws, archi
        $version = $dist->getVersion(); //utile pour le ws, release
        $ihm = $dist->getIhm(); //utile pour l'insert en guaca (yes|no)
    }

    $validRamId = filter_input(INPUT_POST, 'ram', FILTER_SANITIZE_STRING);
    if ($validRamId != null) {
        $ram = RamDAL::findById($validRamId); //sera utile pour l'insertt en base
        $newMachine->setRam($ram);
        $valueRam = $ram->getValeur();  //sera utile pour le ws, ram
    }

    $validStockId = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_STRING);
    if ($validStockId != null) {
        $stock = StockageDAL::findById($validStockId); //sera utile pour l'insertt en base
        $newMachine->setStockage($stock);
        $valueStock = $stock->getValeur(); //sera utile pour le ws, stockage
    }

    $validCpuId = filter_input(INPUT_POST, 'cpu', FILTER_SANITIZE_STRING);
    if ($validCpuId != null) {
        $cpu = CpuDAL::findById($validCpuId); //sera utile pour l'insertt en base
        $newMachine->setCpu($cpu);
        $valueCpu = $cpu->getNbCoeur(); //sera utile pour le ws, cpu
    }

    $validUserId = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING); //sera utile pour l'insert
    if ($validUserId != null) {
        $user = UtilisateurDAL::findById($validUserId); //sert à l'insert
        $newMachine->setUtilisateur($user);
        $loginUtilisateur = $user->getLogin();
        //echo $loginUtilisateur;
    }

    $newDateCreation = date("Y-m-d");
    $newMachine->setDateCreation($newDateCreation);

    $date = date_create($newDateCreation);
    date_add($date, date_interval_create_from_date_string('1 year'));
    $dateExpiration = date_format($date, 'Y-m-d');
    //echo $dateExpiration;
    $newMachine->setDateExpiration($dateExpiration);

    $newMachine->setEtat(2);

    if (UtilisateurDAL::isFull($validUserId) == false) { //vérifie que l'user n'a pas atteint son quota
        if (MachineDAL::findByName($validName) == null) {
            //=====Insertion de la Machine en base=====/ - OK
            $validInsertMachine = MachineDAL::insertOnDuplicate($newMachine);
            if ($validInsertMachine != null) {
                $newLog->setLevel("INFO");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Machine correctement ajoutée en base, d'id: " . $validInsertMachine);
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
               // echo "</br>Machine correctement ajouter en base, d'id: " . $validInsertMachine; // TODO log
                
                //=====Incrémente le nombre de Container de l'utilisateur=====//
                $variable = $user->getNbVm() + 1;
                $user->setNbVm($variable);
                $valid = UtilisateurDAL::insertOnDuplicate($user);
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
                        'uri' => 'http://virt-server/ws_lxc/BuildContainerRequest',
                        'location' => 'http://virt-server/ws_lxc/build_container_ws.php',
                        'trace' => 1,
                        'exceptions' => 0
                    ));
                    $result = $client->__call('buildContainer', array(
                        'nameContainer' => $validName,
                        'archi' => $archi,
                        'distrib' => $distribName,
                        'release' => $version,
                        'ram' => $valueRam,
                        'cpu' => $valueCpu,
                        'stockage' => $valueStock
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
                
               // echo "</br>Le code de retour vaut : " . $code; //TODO log
                if ($code == "0") {
                    //=====Si Container créer======/

                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Conteneur " . $validName . " créé avec succès sur le serveur de Virt");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                  //  echo "</br>Conteneur créer avec succès sur le serveur de Virt"; //TODO log
                    
                    $passwdRoot = substr($result, 1, 10); //récupère le password root de longeur 10 carac en partant du carac n°1
                    $addrIpContainer = substr($result, 11); //recupère l'addresse ip du container renvoyer par SdA
                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("L'adresse IP du conteneur " . $validName . " est: ".$addrIpContainer);
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                   // echo "</br>L'adresse IP du conteneur " . $validName . " est: ".$addrIpContainer;
                    
                    $container = MachineDAL::findById($validInsertMachine);
                    $container->setDescription($container->getDescription() . " Mot de passe du compte root: " . $passwdRoot);
                    $container->setEtat(0);
                    $validUpdateMachine = MachineDAL::insertOnDuplicate($container);
                    if($validUpdateMachine != null){
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
                    $connectionContainer->setConnectionName($validName); //DOnne le nom du container à la connection pour pouvoir l'identifier a la suppression !
                    $connectionContainer->setMaxConnections(null);
                    $connectionContainer->setMaxConnectionsPerUser(null);
                    $connectionContainer->setParent(null);
                    if ($ihm == 'yes') {
                        $newLog->setLevel("INFO");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Connexion vnc pour le contener " . $validName . ".");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        $connectionContainer->setProtocol('vnc');
                    } else if ($ihm == 'no') {
                        $newLog->setLevel("INFO");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Connexion vnc pour le contener " . $validName . ".");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        $connectionContainer->setProtocol('ssh');
                    } else {
                        $newLog->setLevel("ERROR");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Type d'IHM inconnu...");
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
                            //echo "Paramètre username = root de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée."; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre username = root de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                            //echo "Paramètre username = root de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur..."; //TODO log
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
                           // echo "Paramètre password de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée."; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre password de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                           // echo "Paramètre password de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur..."; //TODO log
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
                            //echo "Paramètre hostname = " . $addrIpContainer . " de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée."; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre hostname = " . $addrIpContainer . " de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                           // echo "Paramètre hostname = " . $addrIpContainer . " de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur..."; //TODO log
                        }

                        //set le paramètre port
                        $paramConnectContainer->setParameterName("port");
                        if ($ihm == 'yes') {
                            $paramConnectContainer->setParameterValue(5900);
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Port de connection vnc 5900, pour la connection n°" . $idConnectContainer);
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                           // echo "Port de connection 5900, pour la connection n°" . $idConnectContainer; //TODO log
                        } else if ($ihm == 'no') {
                            $paramConnectContainer->setParameterValue(22);
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Port de connection 22, pour la connection n°" . $idConnectContainer);
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //echo "Port de connection 22, pour la connection n°" . $idConnectContainer; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Erreur, type d'ihm inconnu... Sérieux, comment ça a pu arriver ?!!");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                           exit();
                          //  echo "Erreur, type d'ihm inconnu... Sérieux, comment ça a pu arriver ?!!"; //TODO log
                        }
                        $validInsertParamPort = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                        if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre port de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée.");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                           // echo "Paramètre port de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée."; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre port de la connection (connection n°" . $idConnectContainer . ") non ajoutée.");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                           exit();
                           // echo "Paramètre port de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur..."; //TODO log
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
                            //echo "La permission READ pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !"; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission READ pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                            //echo "La permission READ pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !"; //TODO log
                        }

                        //ajout la permission UPDATE
                        $permConnectContainer->setPermission("UPDATE");
                        $validInsertPermU = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission UPDATE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //echo "La permission UPDATE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !"; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission UPDATE pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                            //echo "La permission UPDATE pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !"; //TODO log
                        }

                        //ajout la permission DELETE
                        $permConnectContainer->setPermission("DELETE");
                        $validInsertPermD = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission DELETE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //echo "La permission DELETE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !"; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission DELETE pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                            //echo "La permission DELETE pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !"; //TODO log
                        }

                        //ajout la permission ADMINISTER
                        $permConnectContainer->setPermission("ADMINISTER");
                        $validInsertPermA = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission ADMINISTER pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //echo "La permission ADMINISTER pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !"; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission ADMINISTER pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            exit();
                            //echo " FINAL : La permission ADMINISTER pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !"; //TODO log
                        }

                        $message = "ok";
                    } else {
                        $newLog->setLevel("ERROR");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Erreur, la connection n'a pas bien était ajouter dans la DB de guaca...");
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        exit();
                        //echo "Erreur, la connection n'a pas bien était ajouter dans la DB de guaca..."; //TODO log
                    }
                } else if ($code == "1") { //If failure pending create of contener
                    $newLog->setLevel("WARN");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Echec de création du conteneur... Contactez le support EVOLVE.");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    echo "</br>Echec de création du conteneur... Contactez le support EVOLVE.";
                    $container = MachineDAL::findById($validInsertMachine);
                    $container->setEtat(1);
                } else { //If fatal error unknow...
                    $newLog->setLevel("WARN");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Code retour inconnu, problème ... Contactez le support EVOLVE !");
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    echo "</br>Code retour inconnu, problème ... Contactez le support EVOLVE !";
                    $container = MachineDAL::findById($validInsertMachine);
                    $container->setEtat(1);
                }
            } else {
                $newLog->setLevel("ERROR");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Echec de l'insertion en base de la Machine");
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                exit();
               // echo "Echec de l'insertion en base de la Machine"; // TODO log
            }
        } else {
            $newLog->setLevel("ERROR");
            $newLog->setLoginUtilisateur($loginUtilisateur);
            $newLog->setMsg("Un container existe déjà avec ce nom (" . $validName . ").");
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            exit();
            //echo "Un container existe déjà avec ce nom..."; // TODO log
        }
    } else {
        $newLog->setLevel("WARN");
        $newLog->setLoginUtilisateur($loginUtilisateur);
        $newLog->setMsg("L'user " . $user->getLogin() . " a atteint son quota de Contenair");
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
      //  echo "L'user " . $user->getLogin() . " a atteint son quota de Contenair."; // TODO log
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";
