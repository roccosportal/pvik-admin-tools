<?php
$this->UseMasterPage(\Pvik\Core\Config::$Config['PvikAdminTools']['BasePath'] .'Views/MasterPages/Master.php');
$ModelTableName = $this->ViewData->Get('ModelTableName');
$SingleHtml = $this->ViewData->Get('SingleHtml');
// set data for the masterpage
$this->ViewData->Set('Title', 'new table entry: '. $ModelTableName);
?>
<?php $this->StartContent('Head'); ?>
<?php $this->EndContent(); ?>
<?php $this->StartContent('Content'); ?>
<div id="tables">
        <h2>new table entry: <?php echo $ModelTableName;?></h2>
        <?php echo $SingleHtml->ToHtml(); ?>
</div>
<?php $this->EndContent(); ?>