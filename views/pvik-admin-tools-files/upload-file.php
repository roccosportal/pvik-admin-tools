<?php
$this->UseMasterPage(Core::$Config['PvikAdminTools']['BasePath'] . 'views/master-pages/master.php');
$ValidationState = $this->ViewData->Get('ValidationState');
$Uploaded = $this->ViewData->Get('Uploaded');
// set data for the masterpage
$this->ViewData->Set('Title', 'upload file');
?>
<?php $this->StartContent('Head'); ?>
<?php $this->EndContent(); ?>
<?php $this->StartContent('Content'); ?>
<div id="files">
    <h2>upload file</h2>
    <?php if ($Uploaded) { ?>
        <div class="form-message-success">
            file uploaded
        </div>
    <?php } ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="field">
            <label class="label-field">name (leave this blank if you don't want to change the name)</label>
            <input class="input-field" type="text" name="name" />
        </div>
        <div class="field">
            <label class="label-field">folder</label>
            <select class="select-field" name="folder">
                <?php
                foreach (Core::$Config['PvikAdminTools']['FileFolders'] as $Folder) {
                    ?>
                    <option><?php echo $Folder ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="field">
            <label class="label-field">file</label>
            <input class="input-field" type="file" name="file" />
            <?php Html::Errorfield($ValidationState, 'File'); ?>
        </div>
        <input class="submit-field" type="submit" name="submit" value="submit" />
    </form>
</div>
<?php $this->EndContent(); ?>