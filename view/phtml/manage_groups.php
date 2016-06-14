<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Groupe_has_MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');

$groupUser = GroupeDAL::findByUser($_SESSION['user_id']);
$sharedContainers = UtilisateurDAL::findShareContener($_SESSION['user_id']);
$unsubscribedGroupList = GroupeDAL::findLessUser($_SESSION['user_id']);

if (!(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && $_SESSION['user_id'] !== false)){
    header('Location: ?page=home');
}
?>

<html>
    <!--Javascript for cloning popup-->
            <script src="./view/javascript/manage_groups.js"></script>

    <body>
        <!--Alert for success/fail of cloning-->
        <div id="alert-clone" class="alert alert-success hidden" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span id ='alert-clone-span'>The request has been send. Go to your <b>Manage Containers</b> page to see the result</span>
        </div>
        
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
                                    <div>
                                        <a href="?page=manage_groups" title="Unsubscribe at this group"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-export" aria-hidden="true"></span></button></a>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"> 
                                <div class="panel panel-info autocollapse">
                                    <div class="panel-heading clickable">
                                        <h2 class="panel-title">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Show Associated Containers List
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
                                                <?php $groupContainer = Groupe_has_MachineDAL::findNomByGroupe($groupList->getId()); ?>
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
                                                            <div>
                                                                <a href="?page=manage_groups" title="Get this container"><button  class="btn btn-default" data-toggle="modal" data-target="#cloneContainer"><span class="glyphicon glyphicon-copy" aria-hidden="true"></span></button></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!--Cloning button pop-up-->
        <div class="modal fade" id="cloneContainer" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Clone container</h4>
                    </div>
                    <div class="modal-body">
                        <!--Form for cloning a container-->
                        <form action="./controller/pages/Clone_Container.php" method="post" >
                            <div class = "form-group">
                                <input id = "idMachine" type = "hidden" class = "form-control" value="<?= $containerGroupList["id"] ?>">
                            </div>
                            <div class="form-group">
                                <label for="nameClonedContainer">Enter a new name to clone this container in your personnal space</label>
                                <input  id="nomMachineClone" type="name" class="form-control" placeholder="Name">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="submit-clone-container" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
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
                                <form action="./controller/pages/Remove_Contener.php" method="post" >
                                    <div class = "form-group">
                                        <input name = "page" type = "hidden" class = "form-control" value = "manage_groups.php">
                                    </div>
                                    <div class = "form-group">
                                        <input name = "idMachine" type = "hidden" class = "form-control" value="<?= $shared["machine_id"] ?>">
                                    </div>
                                    <div class = "form-group">
                                        <input name = "idGroupe" type = "hidden" class = "form-control" value="<?= $shared["groupe_id"] ?>">
                                    </div>
                                    <div>
                                        <a href="?page=manage_groups" title="Remove this container at this group"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></button></a>
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
                                        <input name = "page" type = "hidden" class = "form-control" value = "manage_groups.php">
                                    </div>
                                    <div class = "form-group">
                                        <input name = "idGroupe" type = "hidden" class = "form-control" value="<?= $unsubscribedGroup->getId() ?>">
                                    </div>
                                    <div>
                                        <a href="?page=manage_groups" title="Subscribe at this group"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-import" aria-hidden="true"></span></button></a>
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
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Show Create Group Form
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
                        <a href="?page=manage_groups" title="Create a new group"><button type="submit" class="btn btn-default">Create group</button></a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>