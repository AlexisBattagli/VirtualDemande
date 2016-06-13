<?php

/*
 * Script de déconnexion
 */

// Initialisation de la session
session_start();

// Détruit toutes les variables de session
$_SESSION = array();

//$_COOKIE['user_id']=null;
//$_COOKIE['name']=null;
//$_COOKIE['role_id']=null;

// Si vous voulez détruire complètement la session, effacez également
// le cookie de session.
// Note : cela détruira la session et pas seulement les données de session !
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalement, on détruit la session.
session_destroy();

/*
// On appelle la session
cookie_start();

// On écrase le tableau de session
$_COOKIE = array();

// On détruit la session
cookie_destroy();

// On prévient l'utilisateur
echo 'Vous vous êtes bien deconnectés'; 
*/

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=/VirtualDemande/index.php?page=home' />";