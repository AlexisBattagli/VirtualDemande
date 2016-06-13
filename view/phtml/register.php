<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');

$accountNumber = UtilisateurDAL::GetNumberAvailableUsers();
if($accountNumber == 0) {
    header('Location: ?page=home');
}

?>

<html>
    <body>
        <div>
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

                <!--                Verif a implementer
                                <div class="form-group">
                                    <label for="confirmPasswordRegister">Confirm password</label>
                                    <input type="password" class="form-control" id="confirmPasswordRegister" placeholder="Password">
                                </div>-->

                <!--Date naiss a mettre avec name="date"-->

                <div class="form-group">
                    <label for="birthDateRegister">Birth date </label>
                    <input name="date" type="date" class="form-control" id="birthDateRegister" placeholder="Birth date DD/MM/YYYY">
                </div>

                <button type="submit" class="btn btn-default">Register</button>
            </form>
        </div>
    </body>
</html>