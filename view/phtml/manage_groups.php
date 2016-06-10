<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');

$groupUser = GroupeDAL::findByUser($_COOKIE["user_id"]);
$groupContainer = Groupe_has_MachineDAL::findNomByGroupe($_COOKIE["user_id"]);
$sharedContainers = UtilisateurDAL::findShareContener($_COOKIE["user_id"]);
$unsubscribedGroupList = GroupeDAL::findLessUser($_COOKIE["user_id"]);

echo "<pre>";
var_dump($unsubscribedGroupList);
echo "</pre>";
?>

<html>
    <body>
        <!--Groups in which the user belongs-->
        <div>
            <h2><span class="label label-primary">Groups you belong</span></h2>
            <table class = "table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupUser as $groupList): ?>
                        <tr>
                            <td><?= $groupList->getNom() ?></td>
                            <td><?= $groupList->getDescription() ?></td>
                            <td>
                                <form action="./controller/pages/Unsubscribe_User.php" method="post" >
                                    <div class="form-group">
                                        <input name="page" type="hidden" class="form-control" value="manage_groups.php">
                                    </div>
                                    <div class="form-group">
                                        <input name="idGroupe" type="hidden" class="form-control" value="<?= $groupList->getId() ?>">
                                    </div>
                                    <divl>
                                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-export" aria-hidden="true"></span></button>
                                        </div>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"> 
                                <div class="panel panel-info autocollapse">
                                    <div class="panel-heading clickable">
                                        <h2 class="panel-title">
                                            Containers list
                                        </h2>
                                    </div>
                                    <div class="panel-body">
                                        <table class = "table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>OS</th>
                                                    <th>CPU</th>
                                                    <th>RAM</th>
                                                    <th>Hard drive size</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($groupContainer as $containerGroupList) : ?>
                                                    <tr>
                                                        <td>
                                                            <?= $containerGroupList["nom"] ?>
                                                        </td>
                                                        <td>
                                                            <?= $containerGroupList["os"] ?>
                                                        </td>
                                                        <td>
                                                            <?= $containerGroupList["cpu"] ?>
                                                        </td>
                                                        <td>
                                                            <?= $containerGroupList["ram"] ?>
                                                        </td>
                                                        <td>
                                                            <?= $containerGroupList["stockage"] ?>
                                                        </td>
                                                        <td>
                                                            <?= $containerGroupList["description"] ?>
                                                        </td>
                                                        <td>
                                                            <form action="./controller/pages/Clone_Container.php" method="post" >
                                                                <div class = "form-group">
                                                                    <input name = "page" type = "hidden" class = "form-control" value = "manage_containers.php">
                                                                </div>
                                                                <div class = "form-group">
                                                                    <input name = "idMachine" type = "hidden" class = "form-control" value="<?= $containerGroupList["id"] ?>">
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-copy" aria-hidden="true"></span></button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <!--Shared containers list-->
        <div>
            <h2><span class="label label-primary">Shared containers</span></h2>
            <table class = "table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>OS</th>
                        <th>Description</th>
                        <th>Group</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sharedContainers as $shared) : ?>
                        <tr>
                            <td>
                                <?= $shared["nom"] ?>
                            </td>
                            <td>
                                <?= $shared["os"] ?>
                            </td>
                            <td>
                                <?= $shared["description"] ?>
                            </td>
                            <td>
                                <?= $shared["groupe"] ?>
                            </td>
                            <td>
                                <form action="./controller/pages/Clone_Container.php" method="post" >
                                    <div class = "form-group">
                                        <input name = "page" type = "hidden" class = "form-control" value = "manage_containers.php">
                                    </div>
                                    <div class = "form-group">
                                        <input name = "idMachine" type = "hidden" class = "form-control" value="<?= $shared["machine_id"] ?>">
                                    </div>
                                    <div class = "form-group">
                                        <input name = "idGroupe" type = "hidden" class = "form-control" value="<?= $shared["groupe_id"] ?>">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!--Unsubscribed groups-->
        <div>
            <h2><span class="label label-primary">Groups you're not subscribed to</span></h2>
            <table class = "table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unsubscribedGroupList as $unsubscribedGroup) : ?>
                        <tr>
                            <td>
                                <?= $unsubscribedGroup->getNom() ?>
                            </td>
                            <td>
                                <?= $unsubscribedGroup->getDescription() ?>
                            </td>
                            <td>
                                <form action="./controller/pages/Subscribe_User.php" method="post" >
                                    <div class = "form-group">
                                        <input name = "page" type = "hidden" class = "form-control" value = "manage_containers.php">
                                    </div>
                                    <div class = "form-group">
                                        <input name = "idUser" type = "hidden" class = "form-control" value="<?php echo $_COOKIE["user_id"]; ?>">
                                    </div>
                                    <div class = "form-group">
                                        <input name = "idGroupe" type = "hidden" class = "form-control" value="<?= $unsubscribedGroup->getId() ?>">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-import" aria-hidden="true"></span></button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!--expanding panel for group form-->
        <div class="panel panel-info autocollapse">
            <div class="panel-heading clickable">
                <h2 class="panel-title">
                    Create group
                </h2>
            </div>
            <div class="panel-body">
                <!--Group creation form-->
                <form action="./controller/pages/Create_Group.php" method="post" >
                    <!--Hidden input for return on page-->
                    <div class="form-group">
                        <input name="page" type="hidden" class="form-control" value ="manage_groups.php">
                    </div>
                    <!--Name input-->
                    <div class="form-group">
                        <h4><label for="nameContainer">Name</label></h4>
                        <input name="nom" type="name" class="form-control" id="nameContainer" placeholder="Group name">
                    </div>
                    <!--Personnal description input-->
                    <div>
                        <h4><label>Personnal description</label></h4>
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter a personnal description for your group."></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default">Create container</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>