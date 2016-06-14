<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Distrib_AliasDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/CpuDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/RamDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/StockageDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/UtilisateurDAL.php');

$userId = $_SESSION["user_id"];
$rowsFonctionnal = MachineDAL::findSuccessByUser($_SESSION["user_id"]);
$rowsCreated = MachineDAL::findNotCreatByUser($_SESSION["user_id"]);
$OSDisplayed = Distrib_AliasDAL::findByVisible();
$CPUDisplayed = CpuDAL::findByVisible();
$RAMDisplayed = RamDAL::findByVisible();
$HDDisplayed = StockageDAL::findByVisible();
$canCreate = UtilisateurDAL::isFull($_SESSION["user_id"]);
        
if (!(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && $_SESSION['user_id'] !== false)){
    header('Location: ?page=home');
}        
?>
<html>
    <!--Javascript file for dynamic select-->
    <script src="./view/javascript/manage_containers.js"></script>
    <body>
        <!--List of the containers that are fonctionnals-->
        <div>
            <h2><span class="label label-primary">Fonctionnal containers</span></h2>
            <table class = "table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>OS</th>
                        <th>CPU</th>
                        <th>RAM</th>
                        <th>Hard drive size</th>
                        <th>Description</th>
                        <th>Creation date</th>
                        <th>Expiration date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rowsFonctionnal as $containers) {
                        echo "<tr><td>";
                        echo $containers["nom"];
                        echo "</td><td>";
                        echo $containers["os"];
                        echo "</td><td>";
                        echo $containers["cpu"];
                        echo "</td><td>";
                        echo $containers["ram"];
                        echo "</td><td>";
                        echo $containers["stockage"];
                        echo "</td><td>";
                        echo $containers["description"];
                        echo "</td><td>";
                        echo $containers["date_creation"];
                        echo "</td><td>";
                        echo $containers["date_expiration"];
                        echo "</td><td>";
                        echo "<form action=\"./controller/pages/Delete_Container.php\" method=\"post\" >
                            <div class = \"form-group\">
                                <input name = \"page\" type = \"hidden\" class = \"form-control\" value = \"manage_containers.php\">
                            </div>
                            <div class = \"form-group\">
                                <input name = \"idMachine\" type = \"hidden\" class = \"form-control\" value = \"" . $containers["id"] . "\">
                            </div>
                            <div>
                                <button type=\"submit\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></button>
                            </div>
                        </form>";

                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!--List of the the containers that are in creation-->
        <div>
            <h2><span class="label label-primary">Containers being created</span></h2>
            <table class = "table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>OS</th>
                        <th>CPU</th>
                        <th>RAM</th>
                        <th>Hard drive size</th>
                        <th>Description</th>
                        <th>State</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rowsCreated as $containers) {
                        echo "<tr><td>";
                        echo $containers["nom"];
                        echo "</td><td>";
                        echo $containers["os"];
                        echo "</td><td>";
                        echo $containers["cpu"];
                        echo "</td><td>";
                        echo $containers["ram"];
                        echo "</td><td>";
                        echo $containers["stockage"];
                        echo "</td><td>";
                        echo $containers["description"];
                        echo "</td><td>";
                        //traitement etat à faire
                        if ($containers["etat"] == 2) {
                            echo "Creating";
                        } else {
                            echo "Creation failure";
                        }
                        echo "</td><td>";
                        echo "<form action=\"./controller/pages/Delete_Container.php\" method=\"post\" >
                            <div class = \"form-group\">
                                <input name = \"page\" type = \"hidden\" class = \"form-control\" value = \"manage_containers.php\">
                            </div>
                            <div class = \"form-group\">
                                <input name = \"idMachine\" type = \"hidden\" class = \"form-control\" value = \"" . $containers["id"] . "\">
                            </div>
                            <div>
                                <button type=\"submit\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></button>
                            </div>
                        </form>";

                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!--Container creation panel-->
        <div class="panel panel-info autocollapse">
            <div class="panel-heading <?php if ($canCreate == false) : ?> clickable<?php endif; ?>">
                <h2 class="panel-title">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Show Create Container Form
                </h2>
            </div>
            <div class="panel-body">
                <!--Container creation form-->
                <form action="./controller/pages/Build_Container.php" method="post" >
                    <!--Hidden input for return on page-->
<!--                    <div class="form-group">
                        <input name="page" type="hidden" class="form-control" value ="manage_containers.php">
                    </div>-->
                    <!--Name input-->
                    <div class="form-group">
                        <h4><label for="nameContainer">Name</label></h4>
                        <input name="nameContainer" type="text" pattern=".*[A-Za-z]" class="regex-input" id="nameContainer" placeholder="Container name">
                    </div>
                    <!--OS selector-->
                    <div class="form-group">
                        <h4><label>OS</label></h4>
                        <select name="dist" id="dist" class="form-control">
                            <?php
                            foreach ($OSDisplayed as $OS) {
                                echo "<option value=" . $OS->getId() . ">";
                                echo $OS->getNomComplet();
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!--CPU selector-->
                    <div class="form-group">
                        <h4><label>Number of processors</label></h4>
                        <select name="cpu" id="cpu" class="form-control">
                            <?php
                            foreach ($CPUDisplayed as $CPU) {
                                echo "<option value=" . $CPU->getId() . ">";
                                echo $CPU->getNbCoeur() . " cores";
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!--RAM selector-->
                    <div class="form-group">
                        <h4><label>RAM quantity</label></h4>
                        <select name="ram" id="ram" class="form-control">
                            <?php
                            foreach ($RAMDisplayed as $RAM) {
                                echo "<option value=" . $RAM->getId() . ">";
                                echo $RAM->getValeur() . " MB";
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!--Hard drive space selector-->
                    <div class="form-group">
                        <h4><label>Hard drive space</label></h4>
                        <select name="stock" id="stock" class="form-control">
                            <?php
                            foreach ($HDDisplayed as $HDD) {
                                echo "<option value=" . $HDD->getId() . ">";
                                echo $HDD->getValeur() . " GB";
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!--Personnal description input-->
                    <div>
                        <h4><label>Personnal description</label></h4>
                        <textarea name="descriptionContainer" id="descriptionContainer" class="form-control" rows="3" placeholder="Enter a personnal description for your container."></textarea>
                    </div>
                </form>
                <div>
                    <button id="submit-build-container" class="btn btn-default" data-toggle="modal" data-target="#buildContainer">Create container</button>
                </div>
            </div>
        </div>

        <!--Create container pop up-->
        <div class="modal fade" id="buildContainer" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Request sent</b> list</h4>
                    </div>
                    <div class="modal-body">
                        <span>Your container is in the creation process. You can see it's status in your <b>Containers beeing created</b> list</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Container sharing panel-->
        <div class="panel panel-info autocollapse">
            <div class="panel-heading clickable">
                <h2 class="panel-title">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Show Share Container Form
                </h2>
            </div>
            <div class="panel-body">
                <form action="./controller/pages/Share_Contener.php" method="post" >  <!--action a mettre-->
                    <!--Container selector-->
                    <div class="form-group">
                        <input name="page" type="hidden" class="form-control" value ="manage_containers.php">
                    </div>
                    <div class="form-group">
                        <h4><label>Container to share</label></h4>
                        <select name="idMachine" class="form-control" id="container-to-share">
                            <option selected="selected" disabled> --Select a container to share-- </option>  <!-- rajouter disabled -->
                            <?php foreach ($rowsFonctionnal as $containerList) : ?>
                                <option value="<?= $containerList["id"] ?>"> <?= $containerList["nom"] ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!--Group selector-->
                    <div class="form-group">
                        <h4><label>Group</label></h4>
                        <select name="idGroupe" class="form-control" id="shareable-groups"></select>
                    </div>
                    <div>
                        <h4><label>Commentary</label></h4>
                        <textarea name="comment" class="form-control" rows="3" placeholder="You can enter a commentary here "></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default">Share</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>