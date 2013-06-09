<?php
$this->useMasterPage(\Pvik\Core\Config::$config['PvikAdminTools']['BasePath'] .'Views/MasterPages/Master.php');
// set data for the masterpage
$this->viewData->set('Title', 'tables');
$tableHtml = $this->viewData->get('TableHtml');
?>
<?php $this->startContent('Head'); ?>
<?php $this->endContent(); ?>
<?php $this->startContent('Content'); ?>
<div id="tables">
        <h2>tables</h2>
        <?php 
            echo $tableHtml->toHtml();
        ?>
</div>
<?php $this->endContent(); ?>