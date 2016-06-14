<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');

$userInformations = UtilisateurDAL::findById($_SESSION["user_id"]);

if (!(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && $_SESSION['user_id'] !== false)){
    header('Location: ?page=home');
}
?>

<html>
    <body>
        <!--Name-->
        <div>
            <h2><b>Name : </b> <?=$userInformations->getPrenom() ?> </h2>
        </div>
        <!--Surname-->
        <div>
            <h2><b>Surname : </b> <?=$userInformations->getNom() ?> </h2>
        </div>
        <!--Login-->
        <div>
            <h2><b>Login : </b> <?=$userInformations->getLogin() ?> </h2>
        </div>
        <!--Email-->
        <div>
            <h2><b>E-mail : </b> <?=$userInformations->getMail() ?> </h2>
        </div>
        <!--Birth date-->
        <div>
            <h2><b>Birth date : </b> <?=$userInformations->getDateNaissance() ?> </h2>
        </div>
        <!--Account creation date-->
        <div>
            <h2><b>Account creation date : </b> <?=$userInformations->getDateCreation() ?> </h2>
        </div>
    </body>
</html>

