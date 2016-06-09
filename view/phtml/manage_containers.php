<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/MachineDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/GroupeDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Distrib_AliasDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/CpuDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/RamDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/StockageDAL.php');

$userId=$_COOKIE["user_id"];
$rowsFonctionnal = MachineDAL::findSuccessByUser($_COOKIE["user_id"]);
$rowsCreated = MachineDAL::findNotCreatByUser($_COOKIE["user_id"]);
$OSDisplayed = Distrib_AliasDAL::findByVisible();
$CPUDisplayed = CpuDAL::findByVisible();
$RAMDisplayed = RamDAL::findByVisible();
$HDDisplayed = StockageDAL::findByVisible();


//$groups = GroupeDAL::findByUser($_COOKIE["user_id"]);

//echl
?>
<html>
    <body>
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
                        echo " ";  //Champ à remplir avec bouton action
                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
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

                        echo " ";  //Champ à remplir avec bouton action
                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <!--Container creation panel-->
        <div class="panel panel-info autocollapse">
            <div class="panel-heading clickable">
                <h2 class="panel-title">
                    Create container
                </h2>
            </div>
            <div class="panel-body">
                <!--Container creation form-->
                <form action="./controller/pages/Build_Container.php" method="post" >
                    <!--Hidden input for return on page-->
                    <div class="form-group">
                        <input name="page" type="hidden" class="form-control" value ="manage_containers.php">
                    </div>
<!--                    Hidden input that return user ID A RECUP PAR CONTROLLER-->
                    <div class="form-group">
                        <input name="user" type="hidden" class="form-control" value="<?php echo $userId; ?>" >
                    </div>
                    <!--Name input-->
                    <div class="form-group">
                        <h4><label for="nameContainer">Name</label></h4>
                        <input name="nameContainer" type="name" class="form-control" id="nameContainer" placeholder="Container name">
                    </div>
                    <!--OS selector-->
                    <div class="form-group">
                        <h4><label>OS</label></h4>
                        <select name="dist" class="form-control">
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
                        <select name="cpu" class="form-control">
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
                        <select name="ram" class="form-control">
                            <?php
                            foreach ($RAMDisplayed as $RAM) {
                                echo "<option value=" . $RAM->getId() . ">";  
                                echo $RAM->getValeur() . " GB";
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!--Hard drive space selector-->
                    <div class="form-group">
                        <h4><label>Hard drive space</label></h4>
                        <select name="stock" class="form-control">
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
                        <textarea name="descriptionContainer" class="form-control" rows="3" placeholder="Enter a personnal description for your container."></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default">Create container</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-info autocollapse">
            <div class="panel-heading clickable">
                <h2 class="panel-title">
                    Share container
                </h2>
            </div>
            <div class="panel-body">
                <form action="" method="post" >  <!--action a mettre-->
                    <div class="form-group">
                        <h4><label>Container to share</label></h4>
                        <select class="form-control">
                            <option>Container 1</option>
                            <option>Container 2</option>
                            <option>Container 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <h4><label>Group</label></h4>
                        <select class="form-control">
                            <option>Group 1</option>
                            <option>Group 2</option>
                            <option>Group 3</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default">Share</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>