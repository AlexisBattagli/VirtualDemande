<?php

/**
 * La class Groupe_has_MachineDAL utilise la class Groupe_has_Machine.
 *
 * @author Alexis
 * @version 0.1
 * 
 * Cette class permet de faire,
 * recherche, ajout, modification et suppression de Groupe et Machine Lié
 * Permet de savoir quel sont les machines d'un groupe.
 * quel sont les groupes auxquels appartient une machine
 */
require_once('BaseSingleton.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/class/Groupe_has_Machine.php');

class Groupe_has_MachineDAL {
    /*
     * Retourne l'ensemble des Groupe_has_Machine qui sont en base
     * Lister par Groupe ASC puis Machine ASC
     * 
     * @return array[Groupe_has_Machine] Tous les Groupe_has_Machine sont placés dans un Tableau
     */

    public static function findAll()
    {
        $mesGroupe_has_Machines = array();

        $data = BaseSingleton::select('SELECT '
                        . ' Groupe_has_Machine.Groupe_id as Groupe_id, '
                        . ' Groupe_has_Machine.Machine_id as Machine_id '
                        . ' FROM Groupe_has_Machine'
                        . ' ORDER BY Groupe_has_Machine.Groupe_id ASC, Groupe_has_Machine.machine ASC');

        foreach ($data as $row)
        {
            $groupeHasMachine = new Groupe_has_Machine();
            $groupeHasMachine->hydrate($row);
            $mesGroupe_has_Machines[] = $groupeHasMachine;
        }

        return $mesGroupe_has_Machines;
    }

    /*
     * Retourne l'ensemble des Machines pour un Groupe_id passé en param
     * 
     * @param int $GroupeId
     * @return  array[Groupe_has_Machine]
     */

    public static function findByGroupe($groupeId)
    {
        $mesGroupe_has_Machines = array();

        $data = BaseSingleton::select('SELECT '
                        . ' Groupe_has_Machine.Groupe_id as Groupe_id, '
                        . ' Groupe_has_Machine.Machine_id as Machine_id '
                        . ' FROM Groupe_has_Machine'
                        . ' WHERE Groupe_has_Machine.Groupe_id = ?', array('i', &$groupeId));

        foreach ($data as $row)
        {
            $groupeHasMachine = new Groupe_has_Machine();
            $groupeHasMachine->hydrate($row);
            $mesGroupe_has_Machines[] = $groupeHasMachine;
        }

        return $mesGroupe_has_Machines;
    }

    /*
     * Retourne l'ensemble des Groupe pour un Machine_id passé en param
     * 
     * @param int $MachineId
     * @return  array[Groupe_has_Machine]
     */

    public static function findByMachine($machineId)
    {
        $mesGroupe_has_Machines = array();

        $data = BaseSingleton::select('SELECT '
                        . ' Groupe_has_Machine.Groupe_id as Groupe_id, '
                        . ' Groupe_has_Machine.Machine_id as Machine_id '
                        . ' FROM Groupe_has_Machine'
                        . ' WHERE Groupe_has_Machine.Machine_id = ?', array('i', &$machineId));

        foreach ($data as $row)
        {
            $groupeHasMachine = new Groupe_has_Machine();
            $groupeHasMachine->hydrate($row);
            $mesGroupe_has_Machines[] = $groupeHasMachine;
        }

        return $mesGroupe_has_Machines;
    }

    /*
     * Retourne le Utilisateur_has_Groupe correspondant au couple groupe/utilisateur
     * Ce couple étant unique, il n'y qu'une seul ligne retourner.
     * 
     * @param int groupeId, string utilisateurId
     * @return Utilisateur_has_Groupe | null
     */

    public static function findByGM($groupeId, $machineId)
    {
        $data = BaseSingleton::select('SELECT '
                        . ' Groupe_has_Machine.Groupe_id as Groupe_id, '
                        . ' Groupe_has_Machine.Machine_id as Machine_id '
                        . ' FROM Groupe_has_Machine'
                        . ' WHERE Groupe_has_Machine.Groupe_id = ? AND Groupe_has_Machine.machine_id = ?', array('ii', &$groupeId, &$machineId));
        $groupeHasMachine = new Groupe_has_Machine();

        if (sizeof($data) > 0)
        {
            $groupeHasMachine->hydrate($data[0]);
        }
        else
        {
            $groupeHasMachine = null;
        }
        return $groupeHasMachine;
    }

}
