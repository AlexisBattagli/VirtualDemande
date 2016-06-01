<!DOCTYPE html>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');
session_start();
$pseudo = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_STRING);
if ($pseudo !== null && $password !== null)
{
    $user = UserDAL::connection($pseudo, $password);
    if ($user)
    {
        $_SESSION['user'] = $user->getId();
        $_SESSION['role'] = $user->getRole();
        setcookie("user_id", $_SESSION['user']);
        setcookie("user_role", $_SESSION['role']);
    }
    else
    {
        $_SESSION['user'] = false;
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Le titre</title>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <!--Javascript file for all pages-->
        <script src="./view/javascript/main.js"></script>

        <!--Stylesheet for all pages-->
        <link rel="stylesheet" href="./view/css/main.css">

        <!-- <link rel="icon" type="image/png" href="./view/document/picture/favicon.png" />   -->
    </head>
    <body>
        
        <!-- Nav bar -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="?page=home">EVOLVE</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    
                    
                    <ul class="nav navbar-nav">
                        <li id="what_is_it"><a href="?page=what_is_it">What is it ? <span class="sr-only">(current)</span></a></li>
                        <li id="how_does_it_work"><a href="?page=how_does_it_work">How does it work ?</a></li>
                        <li id="server_disponibility"><a href="?page=server_disponibility">Server disponibility</a></li>
                        <li id="register"><a href="?page=register">Register</a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <form class="navbar-form" role="search">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Username">
                                    <input type="text" class="form-control" placeholder="Password">
                                </div>
                                <button type="submit" class="btn btn-default">Log In</button>
                            </form>
                        </li>
                        <li><a href="#">Sign up</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        
        
        <!-- <div id="left-column" class="col-lg-2"></div>   Décalage à droite de deux colonnes. A garder ?-->
        
        <!-- Page to show -->
        <div id="content" class="col-lg-8">
            <?php
            $page_to_require = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_URL);
            if ($page_to_require !== null)
            {
                require_once './view/phtml/' . $page_to_require . '.php';
            }
            else
            {
                require_once './view/phtml/home.php';
            }
            ?>
            <span id="called_page" class="hidden"><?= $page_to_require ?></span>
        </div>
        <div id="right-column" class="col-lg-2"></div>
    </body>
</html>


