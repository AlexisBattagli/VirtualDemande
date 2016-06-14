<?php

/*
 * Script de déconnexion
 */

// On appelle la session
session_start();

// On écrase le tableau de session
$_SESSION = array();

// On détruit la session
session_destroy();

//Renvoie à la page précédante
    echo "<meta http-equiv='refresh' content='1; url=/VirtualDemande/index.php?page=home' />";