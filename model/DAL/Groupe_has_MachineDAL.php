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
                        . ' groupe_has_machine.groupe_id as groupe_id, '
                        . ' groupe_has_machine.machine_id as machine_id, '
                        . ' groupe_has_machine.commentaire as commentaire '
                        . ' FROM groupe_has_machine'
                        . ' ORDER BY groupe_has_machine.groupe_id ASC, groupe_has_machine.machine_id ASC');

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
                        . ' groupe_has_machine.groupe_id as groupe_id, '
                        . ' groupe_has_machine.machine_id as machine_id, '
                        . ' groupe_has_machine.commentaire as commentaire '
                        . ' FROM groupe_has_machine'
                        . ' WHERE groupe_has_machine.groupe_id = ?', array('i', &$groupeId));

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
                        . ' groupe_has_machine.groupe_id as groupe_id, '
                        . ' groupe_has_machine.machine_id as machine_id, '
                        . ' groupe_has_machine.commentaire as commentaire '
                        . ' FROM groupe_has_machine'
                        . ' WHERE groupe_has_machine.machine_id = ?', array('i', &$machineId));

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
                        . ' groupe_has_machine.groupe_id as groupe_id, '
                        . ' groupe_has_machine.machine_id as machine_id, '
                        . ' groupe_has_machine.commentaire as commentaire '
                        . ' FROM groupe_has_machine'
                        . ' WHERE groupe_has_machine.groupe_id = ? AND groupe_has_machine.machine_id = ?', array('ii', &$groupeId, &$machineId));
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

    /*
     * Insère ou met à jour la Groupe_has_Machine donnée en paramètre.
     * Pour cela on vérifie si le couple d'id de la Machine et du Groupe transmis sont unique.
     * Si le couple return null alors il faut insèrer, sinon update aux id transmis.
     * 
     * @param Utilisateur_has_Groupe utilisateurhasGroupe
     * @return int id
     * L'id de l'objet inséré en base. False si ça a planté
     */

    public static function insertOnDuplicate($groupeHasMachine)
    {

        //Récupère les valeurs de l'objet Groupe_has_Machine passé en para de la méthode
        $groupeId = $groupeHasMachine->getGroupe()->getId(); //int
        $machineId = $groupeHasMachine->getMachine()->getId(); //int
        $commentaire = $groupeHasMachine->getCommentaire();
        
        if (is_null(self::findByGM($groupeId, $machineId)))
        {
            $sql = 'INSERT INTO groupe_has_machine (groupe_id, machine_id, commentaire) '
                    . ' VALUES (?,?,?) ';

            //Prépare les info concernant les types de champs
            $params = array('iis',
                &$groupeId,
                &$machineId,
                &$commentaire
            );
        }
        else
        {
            $sql = 'UPDATE groupe_has_machine '
                    . 'SET groupe_id = ?, '
                    . 'machine_id = ?, '
                    . 'commentaire = ? '
                    . 'WHERE groupe_id = ? AND machine_id = ?';

            //Prépare les info concernant les type de champs
            $params = array('iisii',
                &$groupeId,
                &$machineId,
                &$commentaire,
                &$groupeId,
                &$machineId
            );
        }

        //Exec la requête
        $idInsert = BaseSingleton::insertOrEdit($sql, $params);

        return $idInsert;
    }

    /*
     * Supprime la Groupe_has_Machine correspondant au couple d'id de Groupe/Machine donné en paramètre
     * 
     * @param int $groupeId, int machineId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function delete($groupeId, $machineId)
    {
        $deleted = BaseSingleton::delete('DELETE FROM groupe_has_machine WHERE Groupe_id = ? AND machine_id = ?', array('ii', &$groupeId, &$machineId));
        return $deleted;
    }
    
    /*
     * Supprime la Groupe_has_Machine correspondant a l'id de Machine donné en paramètre
     * 
     * @param int $groupeId, int machineId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function deleteGroupe($groupeId)
    {
        $deleted = BaseSingleton::delete('DELETE FROM groupe_has_machine WHERE Groupe_id = ?', array('i', &$groupeId));
        return $deleted;
    }
    
    /*
     * Supprime la Groupe_has_Machine correspondant au couple à l'id Machine donné en paramètre
     * 
     * @param int $groupeId, int machineId
     * @return bool
     * True si la ligne a bien été supprimée, False sinon
     */

    public static function deleteMachine($machineId)
    {
        $deleted = BaseSingleton::delete('DELETE FROM groupe_has_machine WHERE machine_id = ?', array('i', &$machineId));
        return $deleted;
    }

}
