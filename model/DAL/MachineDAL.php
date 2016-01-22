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
                        . 'machine.ram as ram, '
                        . 'machine.coeur as coeur, '
                        . 'machine.stockage as stockage, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as date_creation '
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
                        . 'machine.Utilisateur_id as utilisateur, '
                        . 'machine.Distrib_Alias_id as distribAlias, '
                        . 'machine.nom as nom, '
                        . 'machine.ram as ram, '
                        . 'machine.coeur as coeur, '
                        . 'machine.stockage as stockage, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as dateCreation '
                        . ' FROM machine'
                . ' ORDER BY machine.Utilisateur_id ASC, machine.Distrib_id ASC');

        foreach ($data as $row)
        {
            $machine = new Machine();
            $machine->hydrate($row);
            $mesMachines[] = $machine;
        }

        return $mesMachines;
    }
    
    /*
     * Retourne la Machine correspondant au couple Distrib_id/Utilisateur_id/nom
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * Il est recherché sans tenir compte de la casse sur nom
     * 
     * @param int distribId, int userId, string nomComplet
     * @return Machine | null
     */
    public static function findByDN($distribAliasId, $userId, $nomComplet)
    {
        $data = BaseSingleton::select('SELECT machine.id as id, '
                        . 'machine.Utilisateur_id as utilisateur, '
                        . 'machine.Distrib_Alias_id as distribAlias, '
                        . 'machine.nom as nom, '
                        . 'machine.ram as ram, '
                        . 'machine.coeur as coeur, '
                        . 'machine.stockage as stockage, '
                        . 'machine.description as description, '
                        . 'machine.date_creation as dateCreation '
                        . ' FROM machine'
                        . ' WHERE machine.Distrib_Alias_id = ? AND machine.Utilisateur_id AND LOWER(machine.nom) = LOWER(?)', array('is', &$distribAliasId, &$userId, &$nomComplet));
        $machine = new Machine();

        if (sizeof($data) > 0)
        {
            $machine->hydrate($data[0]);
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
        $ram = $machine->getRam(); //float
        $coeur = $machine->getCoeur(); //int
        $stockage = $machine->getStockage(); //float
        $description = $machine->getDescription(); //string
        $dateCreation = $machine->getDateCreation(); //string
        $id = $machine->getId(); //int
        if ($id < 0)
        {
            $sql = 'INSERT INTO machine (Utilisateur_id, Distrib_Alias_id, nom, ram, coeur, stockage, description, date_creation) '
                    . ' VALUES (?,?,?,?,?,?,?,?) ';

            //Prépare les info concernant les type de champs
            $params = array('isss',
                &$userId,
                &$distribaliasId,
                &$nom,
                &$ram,
                &$coeur,
                &$stockage,
                &$description,
                &$dateCreation
            );
        }
        else
        {
            $sql = 'UPDATE distrib_alias '
                    . 'SET Utilisateur_id = ?, '
                    . 'Distrib_Alias_id = ?, '
                    . 'nom = ?, '
                    . 'ram = ? '
                    . 'coeur = ? '
                    . 'stockage = ? '
                    . 'description = ? '
                    . 'date_creation = ? '
                    . 'WHERE id = ? ';

            //Prépare les info concernant les type de champs
            $params = array('isssi',
                &$userId,
                &$distribaliasId,
                &$nom,
                &$ram,
                &$coeur,
                &$stockage,
                &$description,
                &$dateCreation,
                &$id
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }
    
    /*
     * Supprime la Distrib_Machine correspondant à l'id donné en paramètre
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
