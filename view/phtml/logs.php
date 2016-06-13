<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Table_logDAL.php');

$all_logs = Table_logDAL::findAll();


?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin EVOLVE: Logs</title>
    </head>
    <body>
        <div>
            <h3><span class="label label-info">Filters</span></h3>
            <form action="" method="post">
                <fieldset>
                    test
                </fieldset>
            </form>
        </div>
        <div>
            <h2><span class="label label-primary">Logs EVOLVE</span></h2>
            <table class ="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Datetime</th>
                        <th>Level</th>
                        <th>User</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $all_log) : ?>
                    <tr>
                        <td><?= $log->getDateTime() ?></td>
                        <td><?= $log->getLevel() ?></td>
                        <td><?= $log->getLoginUtilisateur() ?></td>
                        <td><?= $log->getMsg() ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
