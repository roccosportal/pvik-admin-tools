<?php
$this->UseMasterPage(Core::$Config['PvikAdminTools']['BasePath'] .'views/master-pages/master.php');
$ModelTableName = $this->ViewData->Get('ModelTableName');
$ValidationState = $this->ViewData->Get('ValidationState');
$PresetValues = $this->ViewData->Get('PresetValues');
// set data for the masterpage
$this->ViewData->Set('Title', 'new table entry: '. $ModelTableName);
?>
<?php $this->StartContent('Head'); ?>
<?php $this->EndContent(); ?>
<?php $this->StartContent('Content'); ?>
<div id="tables">
        <h2>new table entry: <?php echo $ModelTableName;?></h2>
        <?php 
        $ModelClassName = ModelTable::Get($ModelTableName)->GetModelClassName();
        // create a empty object
        $Model = new $ModelClassName();
        $PvikAdminToolsSingleHtml = new PvikAdminToolsSingleHtml($Model, $ValidationState);
        $PvikAdminToolsSingleHtml->SetPresetValues($PresetValues);
        echo $PvikAdminToolsSingleHtml->ToHtml();
        ?>
</div>
<?php $this->EndContent(); ?>