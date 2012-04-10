<html>
	<head>
                <title>PvikAdminTools - login</title>
                <?php Html::FaviconLink(Core::$Config['PvikAdminTools']['BasePath'] . 'favicon-1.0.0.ico'); ?>
                <?php Html::StyleSheetLink(Core::$Config['PvikAdminTools']['BasePath'] . '/css/reset.css'); ?>
                <?php Html::StyleSheetLink(Core::$Config['PvikAdminTools']['BasePath'] . '/css/login-1.0.0.css'); ?>
	</head>
	<body>
		<div id="global">
			<div id="main">
                            <form method="POST" action="">
                                <label>PvikAdminTools</label>
                            <table>
                                    <tr><td>Username:</td><td><input type="text" name="username" value="<?php echo $this->ViewData->Get('Username') ?>" /></td></tr>
                                    <tr><td>Password:</td><td><input type="password" name="password" /></td></tr>
                                    <?php if($this->ViewData->ContainsKey('ErrorMessage')){ ?>
                                    <tr><td colspan="2"><span class="error-message"><?php echo $this->ViewData->Get('ErrorMessage'); ?></span></td></tr>
                                    <?php } ?>
                                    <tr><td></td><td><input type="submit" name="login" value="Login" /></td></tr>
                            </table>
                            </form>
			</div>
		</div>
	</body>
</html>