<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Distrib_AliasDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/CpuDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/RamDAL.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/StockageDAL.php');

$OSList = Distrib_AliasDAL::findAll();
$CPUList = CpuDAL::findAll();
$RAMList = RamDAL::findAll();
$HDDList = StockageDAL::findAll();
?>

<html>
    <body>
        <?php if ((isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && $_SESSION['user_id'] !== false) && $_SESSION["role_id"] == 3) : ?>
        <h2><span class="label label-warning">Visible items configuration - Check the items you want to be visible for the user</span></h2>
        <!--OS settings-->
        <form action="./controller/pages/Update_Distrib_Alias.php" method="post" >
            <div>
                <h3><span class="label label-primary">OS parameters</span></h3>
            </div>
            <div class = "form-group">
                <input name = "page" type = "hidden" class = "form-control" value = "forms_administration.php">
            </div>
            <div class="os-parameters-parent">
                <?php foreach ($OSList as $OS): ?>
                    <div class="os-parameters" >
                        <label class="checkbox-inline">
                            <input type="checkbox" name="idsDistribAlias[]" <?php if ($OS->getVisible() == 1) : ?> checked<?php endif; ?> value="<?php echo $OS->getId(); ?>"/> <?php echo $OS->getNomComplet() ;?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <div class="clearfix"></div>
            </div>
            <div class="os-button">
                <a href= "?page=forms_administration" title= "Update the selection of DistribAlias"><button type="submit" class="btn btn-default">Update</button></a>
            </div>
        </form>
        <!--CPU settings-->
        <form action="./controller/pages/Update_Process.php" method="post" >
            <div>
                <h3><span class="label label-primary">CPU parameters</span></h3>
            </div>
            <div class = "form-group">
                <input name = "page" type = "hidden" class = "form-control" value = "forms_administration.php">
            </div>
            <div class="os-parameters-parent">
                <?php foreach ($CPUList as $CPU): ?>
                    <div class="os-parameters" >
                        <label class="checkbox-inline">
                            <input type="checkbox" name="idsCpu[]" <?php if ($CPU->getVisible() == 1) : ?> checked<?php endif; ?> value="<?php echo $CPU->getId(); ?>"/> <?php echo $CPU->getNbCoeur() ;?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <div class="clearfix"></div>
            </div>
            <div class="os-button">
                <a href= "?page=forms_administration" title= "Update the selection of Cpu"><button type="submit" class="btn btn-default">Update</button></a>
            </div>
        </form>
        <!--RAM settings-->
        <form action="./controller/pages/Update_Ram.php" method="post" >
            <div>
                <h3><span class="label label-primary">RAM parameters</span></h3>
            </div>
            <div class = "form-group">
                <input name = "page" type = "hidden" class = "form-control" value = "forms_administration.php">
            </div>
            <div class="os-parameters-parent">
                <?php foreach ($RAMList as $RAM): ?>
                    <div class="os-parameters" >
                        <label class="checkbox-inline">
                            <input type="checkbox" name="idsRam[]" <?php if ($RAM->getVisible() == 1) : ?> checked<?php endif; ?> value="<?php echo $RAM->getId(); ?>"/> <?php echo $RAM->getValeur() ;?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <div class="clearfix"></div>
            </div>
            <div class="os-button">
                <a href= "?page=forms_administration" title= "Update the selection of Ram"><button type="submit" class="btn btn-default">Update</button></a>
            </div>
        </form>
        <!--Hard drive settings-->
        <form action="./controller/pages/Update_Stockage.php" method="post" >
            <div>
                <h3><span class="label label-primary">HDD parameters</span></h3>
            </div>
            <div class = "form-group">
                <input name = "page" type = "hidden" class = "form-control" value = "forms_administration.php">
            </div>
            <div class="os-parameters-parent">
                <?php foreach ($HDDList as $HDD): ?>
                    <div class="os-parameters" >
                        <label class="checkbox-inline">
                            <input type="checkbox" name="idsStockage[]" <?php if ($HDD->getVisible() == 1) : ?> checked<?php endif; ?> value="<?php echo $HDD->getId(); ?>"/> <?php echo $HDD->getValeur() ;?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <div class="clearfix"></div>
            </div>
            <div class="os-button">
                <a href= "?page=forms_administration" title= "Update the selection of Stockage"><button type="submit" class="btn btn-default">Update</button></a>
            </div>
        </form>
        <?php else: ?>
            <script type="text/javascript">
                window.location.href = "./?page=home"
            </script>
        <?php endif; ?>
    </body>
</html>