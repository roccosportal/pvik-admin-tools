<?php
$this->UseMasterPage(Core::$Config['PvikAdminTools']['BasePath'] .'views/master-pages/master.php');
$ModelTableName = $this->ViewData->Get('ModelTableName');
$Model = $this->ViewData->Get('Model');
$ValidationState = $this->ViewData->Get('ValidationState');
// set data for the masterpage
$this->ViewData->Set('Title', 'edit table entry: '. $ModelTableName);
// contains the url for redirecting back after clicking new in a foreign table and submitting the form
$RedirectBackUrl = $this->ViewData->Get('RedirectBackUrl');
?>
<?php $this->StartContent('Head'); ?>
<?php $this->EndContent(); ?>
<?php $this->StartContent('Content'); ?>
<div id="tables">
        <h2>edit table entry: <?php echo $ModelTableName;?></h2>
        <?php 
        $PvikAdminToolsSingleHtml = new PvikAdminToolsSingleHtml($Model, $ValidationState);
        $PvikAdminToolsSingleHtml->SetForeignTableButtonRedirectBackUrl($RedirectBackUrl);
        echo $PvikAdminToolsSingleHtml->ToHtml();
        ?>
</div>
<?php $this->EndContent(); ?>