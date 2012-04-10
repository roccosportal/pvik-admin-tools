<?php
$this->UseMasterPage(Core::$Config['PvikAdminTools']['BasePath'] .'views/master-pages/master.php');
// set data for the masterpage
$this->ViewData->Set('Title', 'tables');
$ModelArray = $this->ViewData->Get('ModelArray');
?>
<?php $this->StartContent('Head'); ?>
<?php $this->EndContent(); ?>
<?php $this->StartContent('Content'); ?>
<div id="tables">
        <h2>tables</h2>
        <?php 
            $PvikAdminToolsTableHtml = new PvikAdminToolsTableHtml($ModelArray);
            echo $PvikAdminToolsTableHtml->ToHtml();
        ?>
</div>
<?php $this->EndContent(); ?>