<?php
$this->useMasterPage(\Pvik\Core\Config::$config['PvikAdminTools']['BasePath'] . 'Views/MasterPages/Master.php');
$validationState = $this->viewData->get('ValidationState');
$uploaded = $this->viewData->get('Uploaded');
// set data for the masterpage
$this->viewData->set('Title', 'upload file');
?>
<?php $this->startContent('Head'); ?>
<?php $this->endContent(); ?>
<?php $this->startContent('Content'); ?>
<div id="files">
    <h2>upload file</h2>
    <?php if ($uploaded) { ?>
        <div class="form-message-success">
            file uploaded
        </div>
    <?php } ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label">name (leave this blank if you don't want to change the name)</label>
            <div class="controls">
                <input class="span8" type="text" name="name" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">folder</label>
            <div class="controls">
                <select class="span8" name="folder">
                    <?php
                    foreach (\Pvik\Core\Config::$config['PvikAdminTools']['FileFolders'] as $folder) {
                        ?>
                        <option><?php echo $folder ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="control-group <?php echo $validationState->getError('File') != null ? 'error' : ''; ?>">
            <label class="control-label">file</label>
            <div class="controls">
                <input class="span8" type="file" name="file" />
                <?php $this->helper->errorfield($validationState, 'File', 'help-inline'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" name="submit" class="btn">submit</button>
            </div>
        </div>

    </form>
</div>
<?php $this->endContent(); ?>