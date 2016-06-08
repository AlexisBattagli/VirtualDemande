<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
$rows = MachineDAL::findByUser($_COOKIE["user_id"]);
?>
<html>
    <body>
        <div>
            <h2><span class="label label-primary">Your containers</span></h2>
            <table class = "table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>OS</th>
                        <th>Name</th>
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
    </body>
</html>