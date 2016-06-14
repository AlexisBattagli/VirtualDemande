<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Table_logDAL.php');

$all_logs = Table_logDAL::findAll();
?>

<html>
    <body>
        <?php if ((isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && $_SESSION['user_id'] !== false) && $_SESSION["role_id"] == 3) : ?>
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
                        <?php foreach ($all_logs as $log) : ?>
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
        <?php else: ?>
            <script type="text/javascript">
                window.location.href = "./?page=home"
            </script>
        <?php endif; ?>
    </body>
</html>
