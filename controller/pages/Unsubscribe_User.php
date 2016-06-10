<?php

/*
 * Envoie de l’id d'un utilisateur et de l'id du groupe 
 * Script (controller) pour supprimer l’utilisateur d’un groupe
 * (=> suppression de toute ces VM partagé dans ce groupe)
 */

//import
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Utilisateur_has_GroupeDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');

//Définition du message renvoyé
$message="error";

//Checker de où il vient

$validPage = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_STRING);

if($validPage == "manage_groups.php")
{
    //=====Vérification de ce qui est renvoyé par le formulaire
    $validIdUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
    //echo "OK pour Id User : ".$validIdUser;

    $validIdGroupe = filter_input(INPUT_POST, 'idGroupe', FILTER_SANITIZE_STRING);
    //echo "OK pour Id Groupe : ".$validIdGroupe;

    //Vérification si l'utilisateur fait partie du groupe
    if(Utilisateur_has_GroupeDAL::findByGU($validIdGroupe,$validIdUser)!=null)
    {
        //echo "Utilisateur est bien dans le groupe";

        //Suppression de l'utilisateur du groupe
        $validDelete=Utilisateur_has_GroupeDAL::delete($validIdGroupe,$validIdUser);

        //Vérification si l'uitilisateur avait des machines partagés dans ce groupe
        $groupeHasMachines=Groupe_has_MachineDAL::findByShareByUserByGroupe($validIdUser,$validIdGroupe);
        if($groupeHasMachines!= null)
        {
            //echo "Utilisateur a des machines dans le groupe";
            //Suppression de la liste des machines de l'utilisateur dans ce groupe
            foreach ($groupeHasMachines as $row)
            {
                $groupeId=$row->getGroupe()->getId();
                //echo $groupeId;
                $machineId=$row->getMachine()->getId();
                //echo $machineId;
                $validDelete=Groupe_has_MachineDAL::delete($groupeId, $machineId);
            }
            
            $message=true;
        }
        else 
        {
            //echo "Utilisateur n'a pas des machines dans le groupe";
        }
    }
    else
    {
        //echo "Utilisateur n'est pas dans le groupe";
    }
}

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=".$_SERVER["HTTP_REFERER"].'&message='.$message. "' />";