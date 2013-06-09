<?php
$this->useMasterPage(\Pvik\Core\Config::$config['PvikAdminTools']['BasePath'] .'Views/MasterPages/Master.php');
// set data for the masterpage
$this->viewData->set('Title', 'tables');
?>
<?php $this->startContent('Head'); ?>
<?php $this->endContent(); ?>
<?php $this->startContent('Content'); ?>
<div id="tables">
        <h2>tables</h2>
        <ul>
            <?php 
            foreach(\Pvik\Core\Config::$config['PvikAdminTools']['Tables'] as $tableName => $table){ 
            ?>
            <li>
                <span class="option-name"><?php $this->helper->link('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' .strtolower($tableName) . ':list/', $tableName); ?>
                </span>
                <span class="option-links">
                    [<?php $this->helper->link('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' .strtolower($tableName) . ':list/', 'list'); ?>|<?php  $this->helper->link('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' .strtolower($tableName) . ':new/', 'new'); ?>]
                </span>
            </li>
            <?php
            }
            ?>
        </ul>
</div>
<?php $this->endContent(); ?>