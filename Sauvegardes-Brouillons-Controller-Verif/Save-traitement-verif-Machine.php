<?php

require_once('/var/www/VirtualDemande/model/class/Cpu.php');
require_once('/var/www/VirtualDemande/model/DAL/CpuDAL.php');

require_once('/var/www/VirtualDemande/model/class/Distrib.php');
require_once('/var/www/VirtualDemande/model/DAL/DistribDAL.php');

require_once('/var/www/VirtualDemande/model/class/Ram.php');
require_once('/var/www/VirtualDemande/model/DAL/RamDAL.php');

require_once('/var/www/VirtualDemande/model/class/Stockage.php');
require_once('/var/www/VirtualDemande/model/DAL/StockageDAL.php');

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
            //echo 'Cpu par défaut a pour ID:'.$defautDistrib->getId();
            //echo 'Nom par défaut :'.$defautDistrib->getNom();
            //echo 'Archi par défaut :'.$defautDistrib->getArchi();
            //echo 'Version par défaut :'.$defautDistrib->getVersion();
            //echo 'Version par défaut :'.$defautDistrib->getIhm();
            //echo 'Visible par défaut :'.$defautDistrib->getVisible();

    //Vérification de findById - OK
            //$defautDistrib=DistribDAL::findById(2);
            //echo 'Cpu par défaut a pour ID:'.$defautDistrib->getId();
            //echo 'Nom par défaut :'.$defautDistrib->getNom();
            //echo 'Archi par défaut :'.$defautDistrib->getArchi();
            //echo 'Version par défaut :'.$defautDistrib->getVersion();
            //echo 'Version par défaut :'.$defautDistrib->getIhm();
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
            //echo 'Cpu par défaut a pour ID:'.$defautDistrib->getId();
            //echo 'Nom par défaut :'.$defautDistrib->getNom();
            //echo 'Archi par défaut :'.$defautDistrib->getArchi();
            //echo 'Version par défaut :'.$defautDistrib->getVersion();
            //echo 'Version par défaut :'.$defautDistrib->getIhm();
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
