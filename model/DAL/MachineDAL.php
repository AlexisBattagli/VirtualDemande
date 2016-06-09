<?php

/**
 * Description of MachineDAL
 *
 * @author Alexis
 * @author Aurelie
 */

require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Machine.php');

class MachineDAL 
{
    /*
     * Retourne la machine par défaut
     * 
     * @return Machine
     */
    public static function findByDefault()
    {
        $id=1;
        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.utilisateur_id as utilisateur_id, '
                        . 'machine.distrib_alias_id as distrib_alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.cpu_id as cpu_id, '
                        . 'machine.ram_id as ram_id, '
                        . 'machine.stockage_id as stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation, '
                        . 'machine.date_expiration as date_expiration, '
                        . 'machine.etat as etat '
                        . ' FROM machine'
                        . ' WHERE machine.id = ?', array('i', &$id));
        $machine = new Machine();
        if (sizeof($data) > 0)
        {
            $machine->hydrate($data[0]);
        }
        else
        {
            $machine = null;
        }
        
        return $machine;
    }
    
/*
     * Retourne la machine correspondant à l'id donnée
     * 
     * @param int $id
     * @return Machine
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.utilisateur_id as utilisateur_id, '
                        . 'machine.distrib_alias_id as distrib_alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.cpu_id as cpu_id, '
                        . 'machine.ram_id as ram_id, '
                        . 'machine.stockage_id as stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation, '
                        . 'machine.date_expiration as date_expiration, '
                        . 'machine.etat as etat '
                        . ' FROM machine'
                        . ' WHERE machine.id = ?', array('i', &$id));
        $machine = new Machine();
        if (sizeof($data) > 0)
        {
            $machine->hydrate($data[0]);
        }
        else
        {
            $machine = null;
        }
        return $machine;
    }

    /*
     * Retourne l'ensemble des Machines d'un utilisateur passé en paramètre
     * 
     * @param int userId
     * @return array[Machine] Toutes les Machines sont placées dans un Tableau
     */
    public static function findByUser($userId)
    {
        $rows = array();

        $data = BaseSingleton::select('SELECT machine.nom as nom, '
                .'distrib_alias.nom_complet as os, '
                .'machine.description as description '
                .'FROM machine, distrib_alias '
                .'WHERE machine.distrib_alias_id = distrib_alias.id AND machine.utilisateur_id = ?', array('i', &$userId));

        foreach ($data as $row)
        {
            $rows[]=$row;
        }

        return $rows;
    }
   
    /*
     * Retourne l'ensemble des Machines d'un utilisateur passé en paramètre qui sont créées
     * 
     * @param int userID
     * @return array[Machine] Toutes les Machines sont placées dans un Tableau
     */
    public static function findSuccessByUser($userId)
    {
        $rows = array();

        $data = BaseSingleton::select('SELECT machine.nom as nom, '
                .'distrib_alias.nom_complet as os, '
                .'cpu.nb_coeur as cpu, '
                .'ram.valeur as ram, '
                .'stockage.valeur as stockage, '
                .'machine.description as description, '
                .'machine.date_creation as date_creation, '
                .'machine.date_expiration as date_expiration, '
                .'FROM machine, distrib_alias, cpu, ram, stockage '
                .'WHERE machine.distrib_alias_id = distrib_alias.id '
                .'AND machine.cpu_id = cpu.id '
                .'AND machine.ram_id = ram.id '
                .'AND machine.stockage_id = stockage.id '
                .' WHERE machine.utilisateur_id = ? AND machine.etat = 0', array('i', &$userId));

        foreach ($data as $row)
        {
            $rows[]=$row;
        }

        return $rows;
    }

    /*
     * Retourne l'ensemble des Machines d'un utilisateur passé en paramètre qui sont en cours de création ou dont l création à échouer
     * 
     * @param int userID
     * @return array[Machine] Toutes les Machines sont placées dans un Tableau
     */
    public static function findNotCreatByUser($userId)
    {
        $mesMachines = array();

        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.utilisateur_id as utilisateur_id, '
                        . 'machine.distrib_alias_id as distrib_alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.cpu_id as cpu_id, '
                        . 'machine.ram_id as ram_id, '
                        . 'machine.stockage_id as stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.etat as etat '
                        . ' FROM machine'
                        . ' WHERE machine.utilisateur_id = ? AND machine.etat != 0', array('i', &$userId));

        foreach ($data as $row)
        {
            $machine = new Machine();
            $machine->hydrate($row);
            $mesMachines[] = $machine;
        }

        return $mesMachines;
    }
    
    /*
     * Renvoie la liste de SES vm partagé (nom de la vm, desc, groupe dans lequel elle est partagé) avec le nom du groupe
     * 
     * @param userId
     * return Object
     */
    
    public static function findByShareByUser($userId)
    {
        $rows = array();
        $data = BaseSingleton::select('SELECT machine.nom as nom, '
                .'distrib_alias.nom_complet as os, '
                .'machine.description as description, '
		.'groupe.nom as groupe '
                .'FROM machine, distrib_alias,groupe, groupe_has_machine, utilisateur  '
                .'WHERE machine.distrib_alias_id = distrib_alias.id '
                .'AND groupe_has_machine.machine_id=machine.id '
                .'AND groupe_has_machine.groupe_id=groupe.id '
                .'AND machine.utilisateur_id=utilisateur.id '
                .'AND utilisateur.id = ?', array('i', &$userId));
        
        foreach ($data as $row)
        {
            $rows[]=$row;
        }

        return $rows;
    }
    
    /*
     * Retourne l'ensemble des Machines qui sont en base
     * 
     * @return array[Machine] Toutes les Machines sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesMachines = array();

        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.utilisateur_id as utilisateur_id, '
                        . 'machine.distrib_alias_id as distrib_alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.cpu_id as cpu_id, '
                        . 'machine.ram_id as ram_id, '
                        . 'machine.stockage_id as stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation, '
                        . 'machine.date_expiration as date_expiration, '
                        . 'machine.etat as etat '
                        . ' FROM machine'
                . ' ORDER BY machine.utilisateur_id ASC, machine.distrib_alias_id ASC');

        foreach ($data as $row)
        {
            $machine = new Machine();
            $machine->hydrate($row);
            $mesMachines[] = $machine;
        }

        return $mesMachines;
    }
    
    /*
     * Retourne la Machine correspondant au couple utilisateur_id/nom
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est recherché sans tenir compte de la casse sur nom
     * 
     * @param int userId, string nom
     * @return Machine | null
     */
    public static function findByDN($userId, $nom)
    {
        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.utilisateur_id as utilisateur_id, '
                        . 'machine.distrib_alias_id as distrib_alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.cpu_id as cpu_id, '
                        . 'machine.ram_id as ram_id, '
                        . 'machine.stockage_id as stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation, '
                        . 'machine.date_expiration as date_expiration, '
                        . 'machine.etat as etat '
                        . ' FROM machine'
                        . ' WHERE machine.utilisateur_id = ? AND LOWER(machine.nom) = LOWER(?)', array('is', &$userId, &$nom));
        $machine = new Machine();

        if (sizeof($data) > 0)
        {
            $machine->hydrate($data[0]);
        }
        else
        {
            $machine=null;
        }
        return $machine;
    }
    
    /*
     * Retourne la Machine correspondant au nom
     * Ce nom de machine étant unique, il n'y qu'une seul ligne retourner.
     * Il est recherché sans tenir compte de la casse sur nom
     * 
     * @param string nom
     * @return Machine | null
     */
    public static function findByName($nom)
    {
        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.utilisateur_id as utilisateur_id, '
                        . 'machine.distrib_alias_id as distrib_alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.cpu_id as cpu_id, '
                        . 'machine.ram_id as ram_id, '
                        . 'machine.stockage_id as stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation, '
                        . 'machine.date_expiration as date_expiration, '
                        . 'machine.etat as etat '
                        . ' FROM machine'
                        . ' WHERE LOWER(machine.nom) = LOWER(?)', array('s', &$nom));
        $machine = new Machine();

        if (sizeof($data) > 0)
        {
            $machine->hydrate($data[0]);
        }
        else
        {
            $machine=null;
        }
        return $machine;
    }
    
    /*
     * Insère ou met à jour la Machine donnée en paramètre.
     * Pour cela on vérifie si l'id de la Machine transmis est sup ou inf à 0.
     * Si l'id est inf à 0 alors il faut insèrer, sinon update à l'id transmis.
     * 
     * @param Machine machine
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($machine)
    {

        //Récupère les valeurs de l'objet machine passé en para de la méthode
        $userId = $machine->getUtilisateur()->getId(); //int
        $distribaliasId = $machine->getDistribAlias()->getId(); //int
        $nom = $machine->getNom(); //string
        $cpu = $machine->getCpu()->getId(); //int
        $ram = $machine->getRam()->getId(); //int
        $stockage = $machine->getStockage()->getId(); //int
        $description = $machine->getDescription(); //string
        $dateCreation = $machine->getDateCreation(); //string
        $dateExpiration = $machine->getDateExpiration(); //string
        $etat=$machine->getEtat();//int
        $id = $machine->getId(); //int
        
        if ($id < 0)
        {
            $sql = 'INSERT INTO machine (utilisateur_id, distrib_alias_id, nom, cpu_id, ram_id, stockage_id, description, date_creation, date_expiration,etat) '
                    . ' VALUES (?,?,?,?,?,?,?,DATE_FORMAT(NOW(),\'%Y-%m-%d\'),DATE_FORMAT(?,\'%Y-%m-%d\'),?) ';

            //Prépare les info concernant les type de champs
            $params = array('iisiiissi',
                &$userId,
                &$distribaliasId,
                &$nom,
                &$cpu,
                &$ram,
                &$stockage,
                &$description,
                &$dateExpiration,
                &$etat
            );
        }
        else
        {
            $sql = 'UPDATE machine '
                    . 'SET utilisateur_id = ?, '
                    . 'distrib_alias_id = ?, '
                    . 'nom = ?, '
                    . 'cpu_id = ?, '
                    . 'ram_id = ?, '
                    . 'stockage_id = ?, '
                    . 'description = ?, '
                    . 'date_creation = ?, '
                    . 'date_expiration = ?, '
                    . 'etat = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('iisiiisssii',
                &$userId,
                &$distribaliasId,
                &$nom,
                &$cpu,
                &$ram,
                &$stockage,
                &$description,
                &$dateCreation,
                &$dateExpiration,
                &$etat,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime la Machine correspondant à l'id donné en paramètre
     * 
     * @param int $id
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($id)
    {
        $deleted = BaseSingleton::delete('DELETE FROM machine WHERE id = ?', array('i', &$id));
        return $deleted;
    }
}
