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
     * Retourne l'alias d'une distrib correspondant à l'id donnée
     * 
     * @param int $id
     * @return Machine
     */
    public static function findById($id)
    {
        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.Utilisateur_id as Utilisateur_id, '
                        . 'machine.Distrib_Alias_id as Distrib_Alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.Cpu_id as Cpu_id, '
                        . 'machine.Ram_id as Ram_id, '
                        . 'machine.Stockage_id as Stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation, '
                        . 'machine.date_expiration as date_expiration '
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
     * Retourne l'ensemble des Machines qui sont en base
     * 
     * @return array[Machine] Toutes les Machines sont placées dans un Tableau
     */
    public static function findAll()
    {
        $mesMachines = array();

        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.Utilisateur_id as Utilisateur_id, '
                        . 'machine.Distrib_Alias_id as Distrib_Alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.Cpu_id as Cpu_id, '
                        . 'machine.Ram_id as Ram_id, '
                        . 'machine.Stockage_id as Stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation, '
                        . 'machine.date_expiration as date_expiration '
                        . ' FROM machine'
                . ' ORDER BY machine.Utilisateur_id ASC, machine.Distrib_Alias_id ASC');

        foreach ($data as $row)
        {
            $machine = new Machine();
            $machine->hydrate($row);
            $mesMachines[] = $machine;
        }

        return $mesMachines;
    }
    
    /*
     * Retourne la Machine correspondant au couple Utilisateur_id/nom
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est recherché sans tenir compte de la casse sur nom
     * 
     * @param int userId, string nom
     * @return Machine | null
     */
    public static function findByDN($userId, $nom)
    {
        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.Utilisateur_id as Utilisateur_id, '
                        . 'machine.Distrib_Alias_id as Distrib_Alias_id, '
                        . 'machine.nom as nom, '
                        . 'machine.Cpu_id as Cpu_id, '
                        . 'machine.Ram_id as Ram_id, '
                        . 'machine.Stockage_id as Stockage_id, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation, '
                        . 'machine.date_expiration as date_expiration '
                        . ' FROM machine'
                        . ' WHERE machine.Utilisateur_id = ? AND LOWER(machine.nom) = LOWER(?)', array('is', &$userId, &$nom));
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
        $id = $machine->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO machine (Utilisateur_id, Distrib_Alias_id, nom, Cpu_id, Ram_id, Stockage_id, description, date_creation, date_expiration) '
                    . ' VALUES (?,?,?,?,?,?,?,?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('iisiiisss',
                &$userId,
                &$distribaliasId,
                &$nom,
                &$cpu,
                &$ram,
                &$stockage,
                &$description,
                &$dateCreation,
                &$dateExpiration
            );
        }
        else
        {
            $sql = 'UPDATE machine '
                    . 'SET Utilisateur_id = ?, '
                    . 'Distrib_Alias_id = ?, '
                    . 'nom = ?, '
                    . 'cpu = ?, '
                    . 'ram = ?, '
                    . 'stockage = ?, '
                    . 'description = ?, '
                    . 'date_creation = ?, '
                    . 'date_expiration = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('iisiiisssi',
                &$userId,
                &$distribaliasId,
                &$nom,
                &$cpu,
                &$ram,
                &$stockage,
                &$description,
                &$dateCreation,
                &$dateExpiration,
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
