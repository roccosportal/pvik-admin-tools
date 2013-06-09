<!DOCTYPE html>
<html>
        <head>
                <?php $this->helper->styleSheetLink(\Pvik\Core\Config::$config['PvikAdminTools']['BasePath'] . 'css/bootstrap.min.css'); ?>
                <?php $this->helper->javaScriptLink('http://code.jquery.com/jquery-1.8.3.min.js'); ?>
                <?php $this->helper->javaScriptLink(\Pvik\Core\Config::$config['PvikAdminTools']['BasePath'] . 'js/bootstrap.min.js'); ?>
                 <meta charset="utf-8" />
                <title>PvikAdminTools - <?php echo $this->viewData->get('Title'); ?></title>
                <?php $this->useContent('Head'); ?>
        </head>
	<body>
		<div class="container-fluid">
                     <div class="row-fluid">
                        <div class="span3"> 
                            <ul class="nav nav-list">
                                <li class="nav-header">account</li>
                                <li><?php $this->helper->link('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] .  'logout/', '[logout]'); ?></li>
                                <li class="nav-header">tables</li>
                                <li><?php $this->helper->link('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/', '[list]'); ?></li>
                                <?php 
                                foreach(\Pvik\Core\Config::$config['PvikAdminTools']['Tables'] as $tableName => $table){ 
                                ?>
                                 <li><?php $this->helper->link('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'tables/' .strtolower($tableName) . ':list/', $tableName); ?> </li>
                                 <?php
                                  }
                                 ?>
                                <li class="nav-header">files</li>
                                <li><?php $this->helper->link('~' .  \Pvik\Core\Config::$config['PvikAdminTools']['Url'] . 'files/upload/', '[upload]'); ?></li>
                            </ul>
                        </div>
                        <div class="span8"><?php $this->useContent('Content'); ?></div>
                    </div>
		</div>
	</body>
</html>
