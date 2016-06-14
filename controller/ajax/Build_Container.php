<?php
session_start();
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

    $newMachine = new Machine();

    //=====Vérification de ce qui est renvoyé par le formulaire
    $validName = filter_input(INPUT_POST, 'nameContainer', FILTER_SANITIZE_STRING); //sera utile pour insert et ws, nameContainer
    if (!is_null($validName)) {
        $newMachine->setNom($validName);
    }

    $validDesc = filter_input(INPUT_POST, 'descriptionContainer', FILTER_SANITIZE_STRING); //utile pour insert
    if (!is_null($validDesc)) {
        $newMachine->setDescription($validDesc);
    }

    $validDistAliasId = filter_input(INPUT_POST, 'dist', FILTER_SANITIZE_STRING);
    if (!is_null($validDistAliasId)) {
        $distAlias = Distrib_AliasDAL::findById($validDistAliasId); //sera utile pour l'insertt en base
        $newMachine->setDistribAlias($distAlias);

        $dist = $distAlias->getDistrib();
        $distribName = $dist->getNom(); //utile pour le ws, distrib
        $archi = $dist->getArchi(); //utile pour le ws, archi
        $version = $dist->getVersion(); //utile pour le ws, release
        $ihm = $dist->getIhm(); //utile pour l'insert en guaca (yes|no)
    }

    $validRamId = filter_input(INPUT_POST, 'ram', FILTER_SANITIZE_STRING);
    if (!is_null($validRamId)) {
        $ram = RamDAL::findById($validRamId); //sera utile pour l'insertt en base
        $newMachine->setRam($ram);
        $valueRam = $ram->getValeur();  //sera utile pour le ws, ram
    }

    $validStockId = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_STRING);
    if (!is_null($validStockId)) {
        $stock = StockageDAL::findById($validStockId); //sera utile pour l'insertt en base
        $newMachine->setStockage($stock);
        $valueStock = $stock->getValeur(); //sera utile pour le ws, stockage
    }

    $validCpuId = filter_input(INPUT_POST, 'cpu', FILTER_SANITIZE_STRING);
    if (!is_null($validCpuId)) {
        $cpu = CpuDAL::findById($validCpuId); //sera utile pour l'insertt en base
        $newMachine->setCpu($cpu);
        $valueCpu = $cpu->getNbCoeur(); //sera utile pour le ws, cpu
    }

    $validUserId = $_SESSION["user_id"]; //sera utile pour l'insert
    if (!is_null($validUserId)) {
        $user = UtilisateurDAL::findById($validUserId); //sert à l'insert
        $newMachine->setUtilisateur($user);
        $loginUtilisateur = $user->getLogin();
    }

    $newDateCreation = date("Y-m-d");
    $newMachine->setDateCreation($newDateCreation);

    $date = date_create($newDateCreation);
    date_add($date, date_interval_create_from_date_string('1 year'));
    $dateExpiration = date_format($date, 'Y-m-d');
    $newMachine->setDateExpiration($dateExpiration);

    $newMachine->setEtat(2);

    if (UtilisateurDAL::isFull($validUserId) == false) { //vérifie que l'user n'a pas atteint son quota
        if (is_null(MachineDAL::findByName($validName))) {
            //=====Insertion de la Machine en base=====/ - OK
            $validInsertMachine = MachineDAL::insertOnDuplicate($newMachine);
            if (!is_null($validInsertMachine)) {
                $newLog->setLevel("INFO");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Machine correctement ajoutée en base, d'id: " . $validInsertMachine);
                $newLog->setDateTime(date('Y/m/d G:i:s'));
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
               
                //=====Incrémente le nombre de Container de l'utilisateur=====//
                $variable = $user->getNbVm() + 1;
                $user->setNbVm($variable);
                $validInsertNewNbCont = UtilisateurDAL::insertOnDuplicate($user);
                if(!is_null($validInsertNewNbCont)){
                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Mise a jour du quota, passe à ".$variable);
                    $newLog->setDateTime(date('Y/m/d G:i:s'));
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                }
                else {
                    $newLog->setLevel("ERROR");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Echec de la mise a jour du quota");
                    $newLog->setDateTime(date('Y/m/d G:i:s'));
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    //Arret
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
                    ini_set('default_socket_timeout', 720); //permet de tenir la connection 5min (300sec)
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
                    $newLog->setDateTime(date('Y/m/d G:i:s'));
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    //Arret
                        exit();
                }

                //=====Analyse de ce qui est renvoyer par le ws (renvoyer par le SdA plus exactement)=====/
                $code = substr($result, 0, 1); //Prend la valeur à la position 0 de la string $result et va jusqu'à atteindre une longeur de 1, soit la première lettre de cette string
                $newLog->setLevel("INFO");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Le code de retour de ws vaut : " . $code);
                $newLog->setDateTime(date('Y/m/d G:i:s'));
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                
                if ($code == "0") {
                    //=====Si Container créer======/

                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Conteneur " . $validName . " créé avec succès sur le serveur de Virt");
                    $newLog->setDateTime(date('Y/m/d G:i:s'));
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    
                    $passwdRoot = substr($result, 1, 10); //récupère le password root de longeur 10 carac en partant du carac n°1
                    $addrIpContainer = substr($result, 11); //recupère l'addresse ip du container renvoyer par SdA
                    $newLog->setLevel("INFO");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("L'adresse IP du conteneur " . $validName . " est: ".$addrIpContainer);
                    $newLog->setDateTime(date('Y/m/d G:i:s'));
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    
                    $container = MachineDAL::findById($validInsertMachine);
                    $container->setDescription($container->getDescription() . " Mot de passe du compte root: " . $passwdRoot);
                    $container->setEtat(0);
                    $validUpdateMachine = MachineDAL::insertOnDuplicate($container);
                    if(!is_null($validUpdateMachine)){
                        $newLog->setLevel("INFO");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Mise à jour de la description du container ".$container->getNom()." en ajoutant le mot de passe root.");
                        $newLog->setDateTime(date('Y/m/d G:i:s'));
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog); 
                    } else {
                        $newLog->setLevel("ERROR");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Echec de la mise à jour de la description du container ".$container->getNom()." pour ajouter le mot de passe root.");
                        $newLog->setDateTime(date('Y/m/d G:i:s'));
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog); 
                        //Arret
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
                        $newLog->setMsg("Connexion vnc pour le container " . $validName . ".");
                        $newLog->setDateTime(date('Y/m/d G:i:s'));
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        $connectionContainer->setProtocol('vnc');
                    } else if ($ihm == 'no') {
                        $newLog->setLevel("INFO");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Connexion ssh pour le container " . $validName . ".");
                        $newLog->setDateTime(date('Y/m/d G:i:s'));
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        $connectionContainer->setProtocol('ssh');
                    } else {
                        $newLog->setLevel("ERROR");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Type d'IHM inconnu...");
                        $newLog->setDateTime(date('Y/m/d G:i:s'));
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        exit();
                    }
                    $idConnectContainer = Guacamole_ConnectionDAL::insertOnDuplicate($connectionContainer);
                    if (!is_null($idConnectContainer)) {//Si création de connection guaca ok
                        //======créer les parameter de la connection guaca=====/
                        $paramConnectContainer = new Guacamole_Connection_Parameter();
                        $paramConnectContainer->setConnection($idConnectContainer);

                        
                        if($ihm == 'no')
                        {
                            //set le paramètre color-scheme
                            $paramConnectContainer->setParameterName("color-scheme");
                            $paramConnectContainer->setParameterValue("green-black");
                            $validInsertParamUsername = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                            if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                                $newLog->setLevel("INFO");
                                $newLog->setLoginUtilisateur($loginUtilisateur);
                                $newLog->setMsg("Paramètre 'color-scheme' = 'green-black' de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée.");
                                $newLog->setDateTime(date('Y/m/d G:i:s'));
                                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            } else {
                                $newLog->setLevel("ERROR");
                                $newLog->setLoginUtilisateur($loginUtilisateur);
                                $newLog->setMsg("Paramètre 'color-scheme' = 'green-black' de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                                $newLog->setDateTime(date('Y/m/d G:i:s'));
                                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                                exit();
                            }

                            //set le paramètre font-size
                            $paramConnectContainer->setParameterName("font-size");
                            $paramConnectContainer->setParameterValue("11");
                            $validInsertParamPwd = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                            if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                                $newLog->setLevel("INFO");
                                $newLog->setLoginUtilisateur($loginUtilisateur);
                                $newLog->setMsg("Paramètre 'font-size' = 11 de la connection (connection n°" . $idConnectContainer . ") correctement ajoutée.");
                                $newLog->setDateTime(date('Y/m/d G:i:s'));
                                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            } else {
                                $newLog->setLevel("ERROR");
                                $newLog->setLoginUtilisateur($loginUtilisateur);
                                $newLog->setMsg("Paramètre 'font-size' = 11 de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                                $newLog->setDateTime(date('Y/m/d G:i:s'));
                                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                                exit();
                            }
                        }
                        
                        //set le paramètre username
                        $paramConnectContainer->setParameterName("username");
                        $paramConnectContainer->setParameterValue("root");
                        $validInsertParamHostname = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                        if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre 'username' = 'root' de la connection (connection n°" . $idConnectContainer . ") correctement ajoutée.");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre 'username' = 'root' de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                                exit();
                        }

                        //set le paramètre password
                        $paramConnectContainer->setParameterName("password");
                        $paramConnectContainer->setParameterValue($passwdRoot);
                        $validInsertParamHostname = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                        if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre 'password' de la connection (connection n°" . $idConnectContainer . ") correctement ajoutée.");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre 'password' de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
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
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre hostname = " . $addrIpContainer . " de la connection (connection n°" . $idConnectContainer . ") non ajoutée, erreur...");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                                exit();
                        }

                        //set le paramètre port
                        $paramConnectContainer->setParameterName("port");
                        if ($ihm == 'yes') {
                            $paramConnectContainer->setParameterValue("5901");
                            $validInsertParamHostname = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Port de connection vnc 5901, pour la connection n°" . $idConnectContainer);
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else if ($ihm == 'no') {
                            $paramConnectContainer->setParameterValue("22");
                            $validInsertParamHostname = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Port de connection ssh 22, pour la connection n°" . $idConnectContainer);
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Erreur, type d'ihm inconnu... Sérieux, comment ça a pu arriver ?!!");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                                exit();
                        }
                       // $validInsertParamPort = Guacamole_Connection_ParameterDAL::insertOnDuplicate($paramConnectContainer);
                        if (!is_null(Guacamole_Connection_ParameterDAL::findByCP($paramConnectContainer->getConnection()->getConnectionId(),$paramConnectContainer->getParameterName()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre port de la connection (connection n°" . $idConnectContainer . ") correctmeent ajoutée.");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                           } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("Paramètre port de la connection (connection n°" . $idConnectContainer . ") non ajoutée.");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //Arret
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
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission READ pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //Arret
                                exit();
                            }

                        //ajout la permission UPDATE
                        $permConnectContainer->setPermission("UPDATE");
                        $validInsertPermU = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission UPDATE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //echo "La permission UPDATE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !"; //TODO log
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission UPDATE pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //Arret
                                exit();
                        }

                        //ajout la permission DELETE
                        $permConnectContainer->setPermission("DELETE");
                        $validInsertPermD = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission DELETE pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission DELETE pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //Arret
                                exit();
                        }

                        //ajout la permission ADMINISTER
                        $permConnectContainer->setPermission("ADMINISTER");
                        $validInsertPermA = Guacamole_Connection_PermissionDAL::insertOnDuplicate($permConnectContainer);
                        if (!is_null(Guacamole_Connection_PermissionDAL::findByUCP($permConnectContainer->getUser()->getUserId(), $permConnectContainer->getConnection()->getConnectionId(), $permConnectContainer->getPermission()))) {
                            $newLog->setLevel("INFO");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission ADMINISTER pour la conneciton n°" . $idConnectContainer . " a bien été ajoutée !");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        } else {
                            $newLog->setLevel("ERROR");
                            $newLog->setLoginUtilisateur($loginUtilisateur);
                            $newLog->setMsg("La permission ADMINISTER pour la conneciton n°" . $idConnectContainer . " n'a pas bien été ajoutée !");
                            $newLog->setDateTime(date('Y/m/d G:i:s'));
                            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                            //Arret
                                exit();
                        }

                        $message = "ok";
                    } else {
                        $newLog->setLevel("ERROR");
                        $newLog->setLoginUtilisateur($loginUtilisateur);
                        $newLog->setMsg("Erreur, la connection n'a pas bien était ajouter dans la DB de guaca...");
                        $newLog->setDateTime(date('Y/m/d G:i:s'));
                        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                        //Arret
                            exit();
                    }
                } else if ($code == "1") { //If failure pending create of contener
                    $newLog->setLevel("WARN");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Echec de création du conteneur... Contactez le support EVOLVE.");
                    $newLog->setDateTime(date('Y/m/d G:i:s'));
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    $container = MachineDAL::findById($validInsertMachine);
                    $container->setEtat(1);
                } else { //If fatal error unknow...
                    $newLog->setLevel("WARN");
                    $newLog->setLoginUtilisateur($loginUtilisateur);
                    $newLog->setMsg("Code retour inconnu, problème ... Contactez le support EVOLVE !");
                    $newLog->setDateTime(date('Y/m/d G:i:s'));
                    $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                    $container = MachineDAL::findById($validInsertMachine);
                    $container->setEtat(1);
                }
            } else {
                $newLog->setLevel("ERROR");
                $newLog->setLoginUtilisateur($loginUtilisateur);
                $newLog->setMsg("Echec de l'insertion en base de la Machine");
                $newLog->setDateTime(date('Y/m/d G:i:s'));
                $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
                //Arret
                    exit();
            }
        } else {
            $newLog->setLevel("ERROR");
            $newLog->setLoginUtilisateur($loginUtilisateur);
            $newLog->setMsg("Un container existe déjà avec ce nom (" . $validName . ").");
            $newLog->setDateTime(date('Y/m/d G:i:s'));
            $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
            //Arret
                    exit();
        }
    } else {
        $newLog->setLevel("WARN");
        $newLog->setLoginUtilisateur($loginUtilisateur);
        $newLog->setMsg("L'user " . $user->getLogin() . " a atteint son quota de Contenair");
        $newLog->setDateTime(date('Y/m/d G:i:s'));
        $validTableLog = Table_logDAL::insertOnDuplicate($newLog);
    }

