<!DOCTYPE html>
<html>
        <head>
		<?php Html::StyleSheetLink(Core::$Config['PvikAdminTools']['BasePath'] . 'css/reset.css'); ?>
                <?php Html::StyleSheetLink(Core::$Config['PvikAdminTools']['BasePath'] . 'css/general-1.0.0.css'); ?>
                <?php Html::JavaScriptLink(Core::$Config['PvikAdminTools']['BasePath'] . 'js/jquery-1.6.1.min.js'); ?>
                <title>PvikAdminTools - <?php echo $this->ViewData->Get('Title'); ?></title>
                <?php Html::FaviconLink(Core::$Config['PvikAdminTools']['BasePath'] . 'favicon-1.0.0.ico'); ?>
                <?php $this->UseContent('Head'); ?>
        </head>
	<body>
		<div id="global">
                    <div id="main">
                        <div id="content">
                            <?php $this->UseContent('Content'); ?>
                        </div>
                        <div id='menu'>
                           <div class="menu-entry">
                                    <h2>account</h2>
                                    <ul>
                                            <li>[<?php Html::Link('~' . Core::$Config['PvikAdminTools']['Url'] .  'logout/', 'logout'); ?>]</li>
                                    </ul>
                            </div>
                            <div class="menu-entry">
                                    <h2>tables</h2>
                                    <ul>
                                        
                                        <li>
                                            [<?php Html::Link('~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/', 'list'); ?>]
                                        </li>
                                        <?php 
                                        foreach(Core::$Config['PvikAdminTools']['Tables'] as $TableName => $Table){ 
                                        ?>
                                        <li><?php Html::Link('~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/' .strtolower($TableName) . ':list/', $TableName); ?>
                                            [<?php Html::Link('~' . Core::$Config['PvikAdminTools']['Url'] . 'tables/' .strtolower($TableName) . ':new/', 'new') ?>]</li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                            </div>
                            <div class="menu-entry">
                                    <h2>files</h2>
                                    <ul>
                                        <li>
                                            [<?php Html::Link('~' . Core::$Config['PvikAdminTools']['Url'] . 'files/upload/', 'upload'); ?>]
                                        </li>
                                    </ul>
                            </div>
                        </div>
                         <div class="clear-fix"></div>
                    </div>
		</div>
	</body>
</html>
