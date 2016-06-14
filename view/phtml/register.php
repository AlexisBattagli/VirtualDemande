<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');

$accountNumber = UtilisateurDAL::GetNumberAvailableUsers();

$message = filter_input(INPUT_GET, 'message', FILTER_SANITIZE_STRING);
?>

<html>
    <body>
        <head>
            <link rel="stylesheet" href="./view/library/bootstrap/css/bootstrap-datetimepicker.css">
            <script type="text/javascript" src="./view/library/bootstrap/js/bootstrap-datetimepicker.min.js"></script>
            <script type="text/javascript" src="../view/library/bootstrap/js/bootstrap-datetimepicker.fr.js"></script>
        </head>
        <div>
            <?php if ($message === 'ok'): ?> 
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span>Your account has been created. You can now log in with the form below.</span>
                </div>
            <?php elseif ($message === 'error'): ?> 
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span>An error has occured. Please try again.</span>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <?php if ($accountNumber > 0) : ?>

            <form action="./controller/pages/Create_User.php" method="post" >

                <div class="form-group">
                    <input name="page" type="hidden" class="form-control" value ="register.php">
                </div>

                <div class="form-group">
                    <label for="nameRegister">Name</label>
                    <input name="prenom" type="name" class="form-control" id="nameRegister" placeholder="Name">
                </div>
                <div class="form-group">
                    <label for="surnameRegister">Surname</label>
                    <input name="nom" type="surname" class="form-control" id="surnameRegister" placeholder="Surname">
                </div>
                <div class="form-group">
                    <label for="usernameRegister">Username</label>
                    <input name="pseudo" type="username" class="form-control" id="usernameRegister" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="emailRegister">Email address</label>
                    <input name="email" type="email" class="form-control" id="emailRegister" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="passwordRegister">Password</label>
                    <input name="password" type="password" class="form-control" id="passwordRegister" placeholder="Password">
                </div>

                <div class="form-group">
                    <label for="passwordRegister">Confirm password</label>
                    <input name="password2" type="password" class="form-control" id="passwordRegister" placeholder="Confirm your password">
                </div>

                <div class="form-group">
                    <label for="birthDateRegister">Birth date</label>
                    <div class="input-group">
                      <input class="form-control date" name="date" id="date" type="date" value="" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                  </div>

                <script type="text/javascript">
                  $('#date').datetimepicker({
                  todayBtn:"true",
                  format:"dd/mm/yyyy", 
                  autoclose:"true",
                  pickerPosition:"bottom-left",
                  minView:"month",
                  language:"fr"
                  });
                </script>
                
                <button type="submit" class="btn btn-default">Register</button>
            </form>
            <?php else : ?>
                <div class="well well-lg">There is no account left for now. Come back later!</div>
            <?php endif; ?>
        </div>
    </body>
</html>