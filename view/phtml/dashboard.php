<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');
$rows = MachineDAL::findByUser($_SESSION["user_id"]);
$groups = GroupeDAL::findByUser($_SESSION["user_id"]);

if (!(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && $_SESSION['user_id'] !== false)){
    header('Location: ?page=home');
}
?>
<html>
    <body>
        <div>
            <h2><span class="label label-primary">Your containers</span></h2>
            <table class = "table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>OS</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rows as $containers) {
                        echo "<tr><td>";
                        echo $containers[0];
                        echo "</td><td>";
                        echo $containers[1];
                        echo "</td><td>";
                        echo $containers[2];
                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div>
            <a class="btn btn-default" href="?page=manage_containers" role="button">Manage your containers</a>
<!--        </div>
        <div>-->
            <a class="btn btn-default" href="?page=manage_containers" role="button">Create a new container</a> <!--Rajouter une ancre-->
        </div>
        <div>
            <h2><span class="label label-primary">Your groups</span></h2>
            <table class = "table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($groups as $group) {
                        echo "<tr><td>";
                        echo $group->getNom();
                        echo "</td><td>";
                        echo $group->getDescription();
                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div>
            <a class="btn btn-default" href="?page=manage_groups" role="button">Manage your groups</a>
        </div>
    </body>
</html>