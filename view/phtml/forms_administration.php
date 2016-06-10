<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/VirtualDemande/model/DAL/Distrib_AliasDAL.php');

$OSList = Distrib_AliasDAL::findAll();

//echo "<pre>";
//var_dump($OSList);
//echo "</pre>";
?>

<html>
    <body>
        <h2><span class="label label-warning">Visible items configuration - Check the items you want to be visible for the user</span></h2>
        <!--OS settings-->
        <form action="./controller/pages/Unsubscribe_User.php" method="post" >
            <div>
                <h3><span class="label label-primary">OS parameters</span></h3>
            </div>
            <div>
                <?php foreach ($OSList as $OS): ?>
                    <label class="checkbox-inline">
                        <input type="checkbox" <?php if ($OS->getVisible() == 1) : ?> checked="checked"<?php endif; ?> id="inlineCheckbox" value=""> <?= $OS->getNomComplet() ?>  <!--Trouver la value-->
                    </label>
                <?php endforeach; ?>
            </div>
            <div>
                <button type="submit" class="btn btn-default">Update</button>
            </div>
        </form>
        <!--CPU settings-->
        <form action="./controller/pages/Build_Container.php" method="post" >
            <div>
                <h3><span class="label label-primary">CPU parameters</span></h3>
            </div>
            <div>
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox1" value="option1"> 1
                </label>
                <button type="submit" class="btn btn-default">Update</button>
            </div>
        </form>
        <!--RAM settings-->
        <form action="./controller/pages/Build_Container.php" method="post" >
            <div>
                <h3><span class="label label-primary">RAM parameters</span></h3>
            </div>
            <div>
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox1" value="option1"> 1
                </label>
                <button type="submit" class="btn btn-default">Update</button>
            </div>
        </form>
        <!--CPU settings-->
        <form action="./controller/pages/Build_Container.php" method="post" >
            <div>
                <h3><span class="label label-primary">Hard Drive parameters</span></h3>
            </div>
            <div>
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox1" value="option1"> 1
                </label>
                <button type="submit" class="btn btn-default">Update</button>
            </div>
        </form>
    </body>
</html>