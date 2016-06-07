<?php

require_once('/var/www/VirtualDemande/model/class/Cpu.php');
require_once('/var/www/VirtualDemande/model/DAL/CpuDAL.php');

require_once('/var/www/VirtualDemande/model/class/Distrib.php');
require_once('/var/www/VirtualDemande/model/DAL/DistribDAL.php');

require_once('/var/www/VirtualDemande/model/class/Ram.php');
require_once('/var/www/VirtualDemande/model/DAL/RamDAL.php');

require_once('/var/www/VirtualDemande/model/class/Stockage.php');
require_once('/var/www/VirtualDemande/model/DAL/StockageDAL.php');

require_once('/var/www/VirtualDemande/model/class/Distrib_Alias.php');
require_once('/var/www/VirtualDemande/model/DAL/Distrib_AliasDAL.php');

require_once('/var/www/VirtualDemande/model/class/Machine.php');
require_once('/var/www/VirtualDemande/model/DAL/MachineDAL.php');

require_once('/var/www/VirtualDemande/model/class/Groupe_has_Machine.php');
require_once('/var/www/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');

require_once('/var/www/VirtualDemande/model/class/Table_log.php');
require_once('/var/www/VirtualDemande/model/DAL/Table_logDAL.php');

require_once('/var/www/VirtualDemande/model/class/Guacamole_Connection.php');
require_once('/var/www/VirtualDemande/model/DAL/Guacamole_ConnectionDAL.php');

require_once('/var/www/VirtualDemande/model/class/Guacamole_Connection_Parameter.php');
require_once('/var/www/VirtualDemande/model/DAL/Guacamole_Connection_ParameterDAL.php');

//Test de tout ce qui touche la table machine

//Vérification des méthodes de CpuDAL : 
    //Vérification de findByDefault - OK
            //$defautCpu=CpuDAL::findByDefault();
            //echo 'Cpu par défaut a pour ID:'.$defautCpu->getId();
            //echo 'Valeur par défaut :'.$defautCpu->getNbCoeur();
            //echo 'Visible par défaut :'.$defautCpu->getVisible();

    //Vérification de findById - OK
            //$defautCpu=CpuDAL::findById(3);
            //echo 'Cpu par défaut a pour ID:'.$defautCpu->getId();
            //echo 'Valeur par défaut :'.$defautCpu->getNbCoeur();
            //echo 'Visible par défaut :'.$defautCpu->getVisible();

    //Vérification de findAll - OK
            //$lesCpu=CpuDAL::findAll();
            //$taille=count($lesCpu);
            //echo 'Nombre cpu :'.$taille;

    //Vérification de findByVisible - OK 
            //$lesCpu=CpuDAL::findByVisible();
            //$taille=count($lesCpu);
            //echo 'Nombre cpu :'.$taille;

    //Vérification de findByNbCoeur - OK
            //$defautCpu=CpuDAL::findByNbCoeur(5);
            //echo 'Cpu par défaut a pour ID:'.$defautCpu->getId();
            //echo 'Valeur par défaut :'.$defautCpu->getNbCoeur();
            //echo 'Visible par défaut :'.$defautCpu->getVisible();
            
    //Vérification d'insertion - OK
        //$defautCpu=new Cpu();
        //$defautCpu->setNbCoeur(1000);
        //$defautCpu->setVisible(1);
        //$validGroupe = CpuDAL::insertOnDuplicate($defautCpu);
        
    //Vérification de update - OK
        //$defautCpu=new Cpu();
        //$defautCpu->setId(2);
        //$defautCpu->setNbCoeur(99);
        //$defautCpu->setVisible(1);
        //$validCpu = CpuDAL::insertOnDuplicate($defautCpu);     
        
    //Vérification de delete - OK
        //$validCpu = CpuDAL::delete(3);

//Vérification des méthodes de DistribDAL : 
    //Vérification de findByDefault - OK
            //$defautDistrib=DistribDAL::findByDefault();
            //echo 'Distrib par défaut a pour ID:'.$defautDistrib->getId();
            //echo 'Nom par défaut :'.$defautDistrib->getNom();
            //echo 'Archi par défaut :'.$defautDistrib->getArchi();
            //echo 'Version par défaut :'.$defautDistrib->getVersion();
            //echo 'Ihm par défaut :'.$defautDistrib->getIhm();
            //echo 'Visible par défaut :'.$defautDistrib->getVisible();

    //Vérification de findById - OK
            //$defautDistrib=DistribDAL::findById(2);
            //echo 'Distrib par défaut a pour ID:'.$defautDistrib->getId();
            //echo 'Nom par défaut :'.$defautDistrib->getNom();
            //echo 'Archi par défaut :'.$defautDistrib->getArchi();
            //echo 'Version par défaut :'.$defautDistrib->getVersion();
            //echo 'Ihm par défaut :'.$defautDistrib->getIhm();
            //echo 'Visible par défaut :'.$defautDistrib->getVisible();

    //Vérification de findAll - OK
            //$lesDistribs=DistribDAL::findAll();
            //$taille=count($lesDistribs);
            //echo 'Nombre distrib :'.$taille;

    //Vérification de findByVisible - OK 
            //$lesDistribs=DistribDAL::findByVisible();
            //$taille=count($lesDistribs);
            //echo 'Nombre cpu :'.$taille;

    //Vérification de findByNAVI - OK
            //$defautDistrib=DistribDAL::findByNAVI("nomib","32bits","mm","pp",1);
            //echo 'Distrib par défaut a pour ID:'.$defautDistrib->getId();
            //echo 'Nom par défaut :'.$defautDistrib->getNom();
            //echo 'Archi par défaut :'.$defautDistrib->getArchi();
            //echo 'Version par défaut :'.$defautDistrib->getVersion();
            //echo 'Ihm par défaut :'.$defautDistrib->getIhm();
            //echo 'Visible par défaut :'.$defautDistrib->getVisible();
            
    //Vérification d'insertion - OK
        //$defautDistrib=new Distrib();
        //$defautDistrib->setNom("testNom");
        //$defautDistrib->setArchi("32bits");
        //$defautDistrib->setVersion("versionTest");
        //$defautDistrib->setIhm("IHMTest");
        //$defautDistrib->setVisible();        
        //$validDistrib = DistribDAL::insertOnDuplicate($defautDistrib);
        
    //Vérification de update - OK
        //$defautDistrib=new Distrib();
        //$defautDistrib->setId(2);
        //$defautDistrib->setNom("testNom");
        //$defautDistrib->setArchi("32bits");
        //$defautDistrib->setVersion("versionTest");
        //$defautDistrib->setIhm("IHMTest");
        //$defautDistrib->setVisible();        
        //$validDistrib = DistribDAL::insertOnDuplicate($defautDistrib);  
        
    //Vérification de delete - OK
        //$validDistrib = DistribDAL::delete(5);

//Vérification des méthodes de RamDAL :
    //Vérification de findByDefault - OK
            //$defautRam=RamDAL::findByDefault();
            //echo 'Ram par défaut a pour ID:'.$defautRam->getId();
            //echo 'Valeur par défaut :'.$defautRam->getValeur();
            //echo 'Visible par défaut :'.$defautRam->getVisible();

    //Vérification de findById - OK
            //$defautRam=RamDAL::findById(3);
            //echo 'Ram par défaut a pour ID:'.$defautRam->getId();
            //echo 'Valeur par défaut :'.$defautRam->getValeur();
            //echo 'Visible par défaut :'.$defautRam->getVisible();

    //Vérification de findAll - OK
            //$lesRam=RamDAL::findAll();
            //$taille=count($lesRam);
            //echo 'Nombre ram :'.$taille;

    //Vérification de findByVisible - OK 
            //$lesRam=RamDAL::findByVisible();
            //$taille=count($lesRam);
            //echo 'Nombre ram :'.$taille;

    //Vérification de findByValeur - OK
            //$defautRam=RamDAL::findByValeur(5000);
            //echo 'Ram par défaut a pour ID:'.$defautRam->getId();
            //echo 'Valeur par défaut :'.$defautRam->getValeur();
            //echo 'Visible par défaut :'.$defautRam->getVisible();
            
    //Vérification d'insertion - OK
        //$defautRam=new Ram();
        //$defautRam->setValeur(1500);
        //$defautRam->setVisible(1);
        //$validRam = RamDAL::insertOnDuplicate($defautRam);
        
    //Vérification de update - OK
        //$defautRam=new Ram();
        //$defautRam->setId(9);
        //$defautRam->setValeur(1050);
        //$defautRam->setVisible(1);
        //$validRam = RamDAL::insertOnDuplicate($defautRam);
        
    //Vérification de delete - OK
        //$validRam = RamDAL::delete(9);

//Vérification des méthodes de StockageDAL :
    //Vérification de findByDefault - OK
            //$defautStockage=StockageDAL::findByDefault();
            //echo 'Stockage par défaut a pour ID:'.$defautStockage->getId();
            //echo 'Valeur par défaut :'.$defautStockage->getValeur();
            //echo 'Visible par défaut :'.$defautStockage->getVisible();

    //Vérification de findById - OK
            //$defautStockage=StockageDAL::findById(2);
            //echo 'Stockage par défaut a pour ID:'.$defautStockage->getId();
            //echo 'Valeur par défaut :'.$defautStockage->getValeur();
            //echo 'Visible par défaut :'.$defautStockage->getVisible();

    //Vérification de findAll - OK
            //$lesStockages=StockageDAL::findAll();
            //$taille=count($lesStockages);
            //echo 'Nombre ram :'.$taille;

    //Vérification de findByVisible - OK 
            //$lesStockages=StockageDAL::findByVisible();
            //$taille=count($lesStockages);
            //echo 'Nombre ram :'.$taille;

    //Vérification de findByValeur - OK
            //$defautStockage=StockageDAL::findByValeur(510);
            //echo 'Stockage par défaut a pour ID:'.$defautStockage->getId();
            //echo 'Valeur par défaut :'.$defautStockage->getValeur();
            //echo 'Visible par défaut :'.$defautStockage->getVisible();
            
    //Vérification d'insertion - OK
        //$defautStockage=new Stockage();
        //$defautStockage->setValeur(1500);
        //$defautStockage->setVisible(1);
        //$validStockage = StockageDAL::insertOnDuplicate($defautStockage);
        
    //Vérification de update - OK
        //$defautStockage=new Stockage();
        //$defautStockage->setId(3);
        //$defautStockage->setValeur(1500);
        //$defautStockage->setVisible(1);
        //$validStockage = StockageDAL::insertOnDuplicate($defautStockage);
        
    //Vérification de delete - OK
        //$validStockage = StockageDAL::delete(3);

//Vérification des méthodes Distrib_AliasDAL : 
    //Vérification de findByDefault - OK
            //$defautDistribAlias=Distrib_AliasDAL::findByDefault();
            //echo 'Distrib Alias par défaut a pour ID:'.$defautDistribAlias->getId();
            //echo 'Distrib par défaut a pour ID:'.$defautDistribAlias->getDistrib()->getId();
            //echo 'Nom Complet par défaut :'.$defautDistribAlias->getNomComplet();
            //echo 'Pseudo par défaut :'.$defautDistribAlias->getPseudo();
            //echo 'Commentaire par défaut :'.$defautDistribAlias->getCommentaire();
            //echo 'Visible par défaut :'.$defautDistribAlias->getVisible();

    //Vérification de findById - OK
            //$defautDistribAlias=Distrib_AliasDAL::findById(2);
            //echo 'Distrib Alias par défaut a pour ID:'.$defautDistribAlias->getId();
            //echo 'Distrib par défaut a pour ID:'.$defautDistribAlias->getDistrib()->getId();
            //echo 'Nom Complet par défaut :'.$defautDistribAlias->getNomComplet();
            //echo 'Pseudo par défaut :'.$defautDistribAlias->getPseudo();
            //echo 'Commentaire par défaut :'.$defautDistribAlias->getCommentaire();
            //echo 'Visible par défaut :'.$defautDistribAlias->getVisible();

    //Vérification de findAll - OK
            //$lesDistribAlias=Distrib_AliasDAL::findAll();
            //$taille=count($lesDistribAlias);
            //echo 'Nombre Distrib Alias :'.$taille;

    //Vérification de findByVisible - OK 
            //$lesDistribAlias=Distrib_AliasDAL::findByVisible();
            //$taille=count($lesDistribAlias);
            //echo 'Nombre Distrib Alias visible:'.$taille;

    //Vérification de findByDN - OK
            //$defautDistribAlias=Distrib_AliasDAL::findByDN(2,"1default_complet_name_disitrib");
            //echo 'Distrib Alias par défaut a pour ID:'.$defautDistribAlias->getId();
            //echo 'Distrib par défaut a pour ID:'.$defautDistribAlias->getDistrib()->getId();
            //echo 'Nom Complet par défaut :'.$defautDistribAlias->getNomComplet();
            //echo 'Pseudo par défaut :'.$defautDistribAlias->getPseudo();
            //echo 'Commentaire par défaut :'.$defautDistribAlias->getCommentaire();
            //echo 'Visible par défaut :'.$defautDistribAlias->getVisible();
            
    //Vérification d'insertion - OK
        //$defautDistribAlias=new Distrib_Alias();
        //$defautDistribAlias->setDistrib(1);
        //$defautDistribAlias->setNomComplet("DistribgTest");
        //$defautDistribAlias->setPseudo("PseudogTest");
        //$defautDistribAlias->setCommentaire("CommentairegTest");
        //$defautDistribAlias->setVisible(1);
        //$validDistribAlias = Distrib_AliasDAL::insertOnDuplicate($defautDistribAlias);
        
    //Vérification de update - OK
        //$defautDistribAlias=new Distrib_Alias();
        //$defautDistribAlias->setId(5);
        //$defautDistribAlias->setDistrib(2);
        //$defautDistribAlias->setNomComplet("Test");
        //$defautDistribAlias->setPseudo("Pseudo");
        //$defautDistribAlias->setCommentaire("Comment");
        //$defautDistribAlias->setVisible(1);
        //$validDistribAlias = Distrib_AliasDAL::insertOnDuplicate($defautDistribAlias);
        
    //Vérification de delete - OK
        //$validDistribAlias = Distrib_AliasDAL::delete(5);

//Vérification des méthodes de MachineDAL :
    //Vérification de findByDefault - OK
            //$defautMachine=MachineDAL::findByDefault();
            //echo 'Machine par défaut a pour ID:'.$defautMachine->getId();
            //echo 'Distrib Alias par défaut :'.$defautMachine->getDistribAlias();
            //echo 'Utilisateur par défaut:'.$defautMachine->getUtilisateur();
            //echo 'Cpu par défaut:'.$defautMachine->getCpu();
            //echo 'Ram par défaut:'.$defautMachine->getRam();
            //echo 'Stockage par défaut:'.$defautMachine->getStockage();
            //echo 'Nom par défaut :'.$defautMachine->getNom();
            //echo 'Description par défaut :'.$defautMachine->getDescription();
            //echo 'Date creation par défaut :'.$defautMachine->getDateCreation();
            //echo 'Date expiration par défaut :'.$defautMachine->getDateExpiration();
            //echo 'Etat par défaut :'.$defautMachine->getEtat();

    //Vérification de findById - OK
            //$defautMachine=MachineDAL::findById(5);
            //echo 'Machine par défaut a pour ID:'.$defautMachine->getId();
            //echo 'Distrib Alias par défaut :'.$defautMachine->getDistribAlias();
            //echo 'Utilisateur par défaut:'.$defautMachine->getUtilisateur();
            //echo 'Cpu par défaut:'.$defautMachine->getCpu();
            //echo 'Ram par défaut:'.$defautMachine->getRam();
            //echo 'Stockage par défaut:'.$defautMachine->getStockage();
            //echo 'Nom par défaut :'.$defautMachine->getNom();
            //echo 'Description par défaut :'.$defautMachine->getDescription();
            //echo 'Date creation par défaut :'.$defautMachine->getDateCreation();
            //echo 'Date expiration par défaut :'.$defautMachine->getDateExpiration();
            //echo 'Etat par défaut :'.$defautMachine->getEtat();

    //Vérification de findAll - OK
            //$lesMachines=MachineDAL::findAll();
            //$taille=count($lesMachines);
            //echo 'Nombre Machine :'.$taille;
            
    //Vérification de findByUser($userId) - OK
            //$lesMachines=MachineDAL::findByUser(14);
            //$taille=count($lesMachines);
            //echo 'Nombre Machine :'.$taille;

    //Vérification de findSuccessByUser($userId) - OK
            //$lesMachines=MachineDAL::findSuccessByUser(1);
            //$taille=count($lesMachines);
            //echo 'Nombre Machine :'.$taille;

    //Vérification de findNotCreatByUser($userId) - OK
            //$lesMachines=MachineDAL::findNotCreatByUser(1);
            //$taille=count($lesMachines);
            //echo 'Nombre Machine :'.$taille;
            
    //Vérification de findByDN($userId, $nom) - OK
            //$defautMachine=MachineDAL::findByDN(1,"1default_name_machine");
            //echo 'Machine par défaut a pour ID:'.$defautMachine->getId();
            //echo 'Distrib Alias par défaut :'.$defautMachine->getDistribAlias();
            //echo 'Utilisateur par défaut:'.$defautMachine->getUtilisateur();
            //echo 'Cpu par défaut:'.$defautMachine->getCpu();
            //echo 'Ram par défaut:'.$defautMachine->getRam();
            //echo 'Stockage par défaut:'.$defautMachine->getStockage();
            //echo 'Nom par défaut :'.$defautMachine->getNom();
            //echo 'Description par défaut :'.$defautMachine->getDescription();
            //echo 'Date creation par défaut :'.$defautMachine->getDateCreation();
            //echo 'Date expiration par défaut :'.$defautMachine->getDateExpiration();
            //echo 'Etat par défaut :'.$defautMachine->getEtat();
            
    //Vérification d'insertion - OK
            //$defautMachine=new Machine();
            //$defautMachine->setDistribAlias(2);
            //$defautMachine->setUtilisateur(6);
            //$defautMachine->setCpu(2);
            //$defautMachine->setRam(2);
            //$defautMachine->setStockage(2);
            //$defautMachine->setNom("mamachine");
            //$defautMachine->setDescription("MAMACHINE");
            //$defautMachine->setDateCreation("2015-05-06");
            //$defautMachine->setDateExpiration("2016-05-06");
            //$defautMachine->setEtat(1);
            //$validMachine = MachineDAL::insertOnDuplicate($defautMachine);
            
        
    //Vérification de update - OK
            //$defautMachine=new Machine();
            //$defautMachine->setId(7);
            //$defautMachine->setDistribAlias(1);
            //$defautMachine->setUtilisateur(1);
            //$defautMachine->setCpu(1);
            //$defautMachine->setRam(1);
            //$defautMachine->setStockage(1);
            //$defautMachine->setNom("11mamachine");
            //$defautMachine->setDescription("11MAMACHINE");
            //$defautMachine->setDateCreation("2005-05-06");
            //$defautMachine->setDateExpiration("2006-05-06");
            //$defautMachine->setEtat(1);
            //$validMachine = MachineDAL::insertOnDuplicate($defautMachine);
        
    //Vérification de delete - OK
            //$validMachine = MachineDAL::delete(7);

//Vérification des méthodes de Groupe_has_MachineDAL :
    //Vérification de findAll - OK
            //$lesGroupeHasMachines=Groupe_has_MachineDAL::findAll();
            //$taille=count($lesGroupeHasMachines);
            //echo 'Nombre groupe has machine :'.$taille;

    //Vérification de findByGroupe($groupeId) - OK
            //$lesGroupeHasMachines=Groupe_has_MachineDAL::findByGroupe(1);
            //$taille=count($lesGroupeHasMachines);
            //echo 'Nombre groupe has machine :'.$taille;
            
    //Vérification de findByMachine($machineId) - OK
            //$lesGroupeHasMachines=Groupe_has_MachineDAL::findByMachine(5);
            //$taille=count($lesGroupeHasMachines);
            //echo 'Nombre groupe has machine :'.$taille;

    //Vérification de findByGM($groupeId, $machineId) - OK
            //$lesGroupeHasMachines=Groupe_has_MachineDAL::findByGM(1,5);
            //$taille=count($lesGroupeHasMachines);
            //echo 'Nombre groupe has machine :'.$taille;
            //echo 'Machine :'.$lesGroupeHasMachines->getMachine()->getId();
            //echo 'Groupe :'.$lesGroupeHasMachines->getGroupe()->getId();
            //echo 'Commentaire :'.$lesGroupeHasMachines->getCommentaire();
            
    //Vérification d'insertion - OK
        //$GroupeHasMachine=new Groupe_has_Machine();
        //$GroupeHasMachine->setGroupe(5);
        //$GroupeHasMachine->setMachine(5);
        //$GroupeHasMachine->setCommentaire("CommentaireTest");     
        //$validGroupeHasMachine = Groupe_has_MachineDAL::insertOnDuplicate($GroupeHasMachine);
        
    //Vérification de update - OK
        //$GroupeHasMachine=new Groupe_has_Machine();
        //$GroupeHasMachine->setGroupe(5);
        //$GroupeHasMachine->setMachine(5);
        //$GroupeHasMachine->setCommentaire("Test++");     
        //$validGroupeHasMachine = Groupe_has_MachineDAL::insertOnDuplicate($GroupeHasMachine);
        
    //Vérification de delete($groupeId, $machineId) - OK
        //$validGroupeHasMachine = Groupe_has_MachineDAL::delete(1,6);

    //Vérification de deleteGroupe($groupeId) - OK
        //$validGroupeHasMachine = Groupe_has_MachineDAL::deleteGroupe(1);
        
    //Vérification de deleteMachine($machineId) - OK
        //$validGroupeHasMachine = Groupe_has_MachineDAL::deleteMachine(5);

//Vérification des méthodes de Table_logDAL :
    //INSERT INTO table_log (date_heure, action, code_retour, utilisateur, machine) VALUES ("23::15:02","CREATE",0,1,6)
    //Vérification de findByDefault - OK
            //$defautTableLog=Table_logDAL::findByDefault();
            //echo 'Table log par défaut a pour ID:'.$defautTableLog->getId();
            //echo 'Date_heure par défaut :'.$defautTableLog->getDateHeure();
            //echo 'Action par défaut :'.$defautTableLog->getAction();
            //echo 'Code_retour par défaut :'.$defautTableLog->getCodeRetour();
            //echo 'Utilisateur par défaut :'.$defautTableLog->getUtilisateur();
            //echo 'Machine par défaut :'.$defautTableLog->getMachine();

    //Vérification de findById - OK
            //$defautTableLog=Table_logDAL::findById(2);
            //echo 'Table log par défaut a pour ID:'.$defautTableLog->getId();
            //echo 'Date_heure par défaut :'.$defautTableLog->getDateHeure();
            //echo 'Action par défaut :'.$defautTableLog->getAction();
            //echo 'Code_retour par défaut :'.$defautTableLog->getCodeRetour();
            //echo 'Utilisateur par défaut :'.$defautTableLog->getUtilisateur();
            //echo 'Machine par défaut :'.$defautTableLog->getMachine();

    //Vérification de findAll - OK
            //$lesTableLogs=Table_logDAL::findAll();
            //$taille=count($lesTableLogs);
            //echo 'Nombre Table_log :'.$taille;

    //Vérification de findByMUDAC($machine, $utilisateur, $dateHeure, $action, $codeRetour) - OK
            //$defautTableLog=Table_logDAL::findByMUDAC(6,1,"2021-12-17 00:00:00","CREATE",1);
            //echo 'Table log par défaut a pour ID:'.$defautTableLog->getId();
            //echo 'Date_heure par défaut :'.$defautTableLog->getDateHeure();
            //echo 'Action par défaut :'.$defautTableLog->getAction();
            //echo 'Code_retour par défaut :'.$defautTableLog->getCodeRetour();
            //echo 'Utilisateur par défaut :'.$defautTableLog->getUtilisateur();
            //echo 'Machine par défaut :'.$defautTableLog->getMachine();
            
    //Vérification d'insertion - OK
            //$defautTableLog=new Table_log();
            //$defautTableLog->setDateHeure("2222/12/02 12:00:01");
            //$defautTableLog->setAction("DELETE");
            //$defautTableLog->setCodeRetour("2");
            //$defautTableLog->setUtilisateur("4");
            //$defautTableLog->setMachine("9");
            //$validTableLog = Table_logDAL::insertOnDuplicate($defautTableLog);
        
    //Vérification de update - OK
            //$defautTableLog=new Table_log();
            //$defautTableLog->setId(3);
            //$defautTableLog->setDateHeure("1500/12/02 12:25:01");
            //$defautTableLog->setAction("UPDATE");
            //$defautTableLog->setCodeRetour("0");//
            //$defautTableLog->setUtilisateur("2");//
            //$defautTableLog->setMachine("5");//
            //$validTableLog = Table_logDAL::insertOnDuplicate($defautTableLog);
        
    //Vérification de delete - OK
        //$validTableLog = Table_logDAL::delete(5);

//Vérification des méthodes de Guacamole_ConnectionDAL :
        //Vérification de findByDefault - OK
            //$defautGucamoleConnection=Guacamole_ConnectionDAL::findByDefault(); 
            //echo 'GucamoleConnection par défaut a pour ID:'.$defautGucamoleConnection->getConnectionId();
            //echo 'Connection name par défaut :'.$defautGucamoleConnection->getConnectionName();
            //echo 'Parent ID par défaut :'.$defautGucamoleConnection->getParent()->getId(); - Il est null
            //echo 'Protocol par défaut :'.$defautGucamoleConnection->getProtocol();
            //echo 'max connection par défaut :'.$defautGucamoleConnection->getMaxConnections();
            //echo 'max_connections_per_user par défaut :'.$defautGucamoleConnection->getMaxConnectionsPerUser();

    //Vérification de findById - OK
            //$defautGucamoleConnection=Guacamole_ConnectionDAL::findById(3); 
            //echo 'GucamoleConnection par défaut a pour ID:'.$defautGucamoleConnection->getConnectionId();
            //echo 'Connection name par défaut :'.$defautGucamoleConnection->getConnectionName();
            //echo 'Parent ID par défaut :'.$defautGucamoleConnection->getParent()->getId();
            //echo 'Protocol par défaut :'.$defautGucamoleConnection->getProtocol();
            //echo 'max connection par défaut :'.$defautGucamoleConnection->getMaxConnections();
            //echo 'max_connections_per_user par défaut :'.$defautGucamoleConnection->getMaxConnectionsPerUser();

    //Vérification de findAll - OK
            //$lesGucamoleConnections=Guacamole_ConnectionDAL::findAll(); 
            //$taille=count($lesGucamoleConnections);
            //echo 'Nombre GucamoleConnections :'.$taille;
            
    //Vérification de findByCP - OK
            //$defautGucamoleConnection=Guacamole_ConnectionDAL::findByCP("sshUser","ssh"); echo "ok";
            //$taille=count($defautGucamoleConnection);
            //echo 'Nombre GucamoleConnections :'.$taille;
            //echo 'GucamoleConnection par défaut a pour ID:'.$defautGucamoleConnection->getConnectionId();
            //echo 'Connection name par défaut :'.$defautGucamoleConnection->getConnectionName();
            //echo 'Parent ID par défaut :'.$defautGucamoleConnection->getParent()->getId();
            //echo 'Protocol par défaut :'.$defautGucamoleConnection->getProtocol();
            //echo 'max connection par défaut :'.$defautGucamoleConnection->getMaxConnections();
            //echo 'max_connections_per_user par défaut :'.$defautGucamoleConnection->getMaxConnectionsPerUser();

    //Vérification d'insertion - OK
            //$defautGucamoleConnection=new Guacamole_Connection();
            //$defautGucamoleConnection->setConnectionName("ConnectionTest");
            //$defautGucamoleConnection->setProtocol("telnet");
            //$defautGucamoleConnection->setMaxConnections(3);
            //$defautGucamoleConnection->setMaxConnectionsPerUser(2);
            //$validGucamoleConnection = Guacamole_ConnectionDAL::insertOnDuplicate($defautGucamoleConnection);
        
    //Vérification de update - OK
            //$defautGucamoleConnection=new Guacamole_Connection();
            //$defautGucamoleConnection->setConnectionId(3);
            //$defautGucamoleConnection->setConnectionName("Test");
            //$defautGucamoleConnection->setProtocol("ssh");
            //$defautGucamoleConnection->setMaxConnections(6);
            //$defautGucamoleConnection->setMaxConnectionsPerUser(1);
            //$validGucamoleConnection = Guacamole_ConnectionDAL::insertOnDuplicate($defautGucamoleConnection);
        
    //Vérification de delete - OK
            //$validGucamoleConnection = Guacamole_ConnectionDAL::delete(6);

//Vérification des méthodes de Guacamole_Connection_ParameterDAL :
    //Vérification de findAll - OK
            //$lesGucamoleConnectionParameters=Guacamole_Connection_ParameterDAL::findAll(); 
            //$taille=count($lesGucamoleConnectionParameters);
            //echo 'Nombre GucamoleConnections :'.$taille;
            
    //Vérification de findByConnection($connectionId) - OK
            //$lesGucamoleConnectionParameters=Guacamole_Connection_ParameterDAL::findByConnection(1); 
            //$taille=count($lesGucamoleConnectionParameters);
            //echo 'Nombre GucamoleConnections :'.$taille;
            
    //Vérification de findByCP($connectionId, $parameterName) - OK
            //$gucamoleConnectionParameter=Guacamole_Connection_ParameterDAL::findByCP(1, "font-size"); 
            //$taille=count($gucamoleConnectionParameter);
            //echo 'Nombre GucamoleConnections :'.$taille;
            //echo "Connection ID : ".$gucamoleConnectionParameter->getConnection()->getConnectionId();
            //echo "Parameter Name : ".$gucamoleConnectionParameter->getParameterName();
            //echo "Parameter Value : ".$gucamoleConnectionParameter->getParameterValue();
    
    //Vérification d'insertion - OK
            //$gucamoleConnectionParameter=new Guacamole_Connection_Parameter(); 
            //$gucamoleConnectionParameter->setConnection(3); 
            //$gucamoleConnectionParameter->setParameterName("ParameterName"); 
            //$gucamoleConnectionParameter->setParameterValue("ParameterValeue"); 
            //$validGucamoleConnectionParameter = Guacamole_Connection_ParameterDAL::insertOnDuplicate($gucamoleConnectionParameter);
        
    //Vérification de update - OK
            //$gucamoleConnectionParameter=new Guacamole_Connection_Parameter(); 
            //$gucamoleConnectionParameter->setConnection(3); 
            //$gucamoleConnectionParameter->setParameterName("ParameterName"); 
            //$gucamoleConnectionParameter->setParameterValue("A+++"); 
            //$validGucamoleConnectionParameter = Guacamole_Connection_ParameterDAL::insertOnDuplicate($gucamoleConnectionParameter);
            
    //Vérification de delete($connectionId, $parameterName) - OK
            //$validGucamoleConnectionParameter = Guacamole_Connection_ParameterDAL::delete(3,"ParameterName");

    //Vérification de deleteConnection($connectionId) - OK
            //$validGucamoleConnectionParameter = Guacamole_Connection_ParameterDAL::deleteConnection(3);

//Vérification des méthodes de Guacamole_Connection_PermissionDAL :
    //Vérification de findAll - 
            //$lesGucamoleConnectionParameters=Guacamole_Connection_ParameterDAL::findAll(); 
            //$taille=count($lesGucamoleConnectionParameters);
            //echo 'Nombre GucamoleConnections :'.$taille;
            
    //Vérification de findByConnection($connectionId) - 
            //$lesGucamoleConnectionParameters=Guacamole_Connection_ParameterDAL::findByConnection(1); 
            //$taille=count($lesGucamoleConnectionParameters);
            //echo 'Nombre GucamoleConnections :'.$taille;
            
    //Vérification de findByCP($connectionId, $parameterName) - 
            //$gucamoleConnectionParameter=Guacamole_Connection_ParameterDAL::findByCP(1, "font-size"); 
            //$taille=count($gucamoleConnectionParameter);
            //echo 'Nombre GucamoleConnections :'.$taille;
            //echo "Connection ID : ".$gucamoleConnectionParameter->getConnection()->getConnectionId();
            //echo "Parameter Name : ".$gucamoleConnectionParameter->getParameterName();
            //echo "Parameter Value : ".$gucamoleConnectionParameter->getParameterValue();
    
    //Vérification d'insertion - 
            //$gucamoleConnectionParameter=new Guacamole_Connection_Parameter(); 
            //$gucamoleConnectionParameter->setConnection(3); 
            //$gucamoleConnectionParameter->setParameterName("ParameterName"); 
            //$gucamoleConnectionParameter->setParameterValue("ParameterValeue"); 
            //$validGucamoleConnectionParameter = Guacamole_Connection_ParameterDAL::insertOnDuplicate($gucamoleConnectionParameter);
        
    //Vérification de update - 
            //$gucamoleConnectionParameter=new Guacamole_Connection_Parameter(); 
            //$gucamoleConnectionParameter->setConnection(3); 
            //$gucamoleConnectionParameter->setParameterName("ParameterName"); 
            //$gucamoleConnectionParameter->setParameterValue("A+++"); 
            //$validGucamoleConnectionParameter = Guacamole_Connection_ParameterDAL::insertOnDuplicate($gucamoleConnectionParameter);
            
    //Vérification de delete($connectionId, $parameterName) - 
            //$validGucamoleConnectionParameter = Guacamole_Connection_ParameterDAL::delete(3,"ParameterName");

    //Vérification de deleteConnection($connectionId) - 
            //$validGucamoleConnectionParameter = Guacamole_Connection_ParameterDAL::deleteConnection(3);        