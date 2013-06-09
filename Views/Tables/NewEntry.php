<?php
$this->useMasterPage(\Pvik\Core\Config::$config['PvikAdminTools']['BasePath'] .'Views/MasterPages/Master.php');
$modelTableName = $this->viewData->get('ModelTableName');
$singleHtml = $this->viewData->get('SingleHtml');
// set data for the masterpage
$this->viewData->set('Title', 'new table entry: '. $modelTableName);
?>
<?php $this->startContent('Head'); ?>
<?php $this->endContent(); ?>
<?php $this->startContent('Content'); ?>
<div id="tables">
        <h2>new table entry: <?php echo $modelTableName;?></h2>
        <?php echo $singleHtml->toHtml(); ?>
</div>
<?php $this->endContent(); ?>