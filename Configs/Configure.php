<?php
if(!isset(self::$config['PvikAdminTools'])){
	throw new \Exception('PvikAdminTools: PvikAdminTools must be set in config');
}

// gets and sets the base path of PvikAdminTools
self::$config['PvikAdminTools']['BasePath'] = '~' . str_replace('Configs/Configure.php', '', str_replace(getcwd(), '',  realpath ( __FILE__ )));

// add routes
if(!isset(self::$config['Routes'])){
    self::$config['Routes'] = array();
}
array_push(self::$config['Routes'], array ('Url' => self::$config['PvikAdminTools']['Url'] , 'Controller' => '\\PvikAdminTools\\Controllers\\Tables', 'Action' => 'Index'));
array_push(self::$config['Routes'], array ('Url' => self::$config['PvikAdminTools']['Url'] . 'tables/', 'Controller' => '\\PvikAdminTools\\Controllers\\Tables', 'Action' => 'Index'));
array_push(self::$config['Routes'], array ('Url' => self::$config['PvikAdminTools']['Url'] . 'tables/{parameters}/', 'Controller' => '\\PvikAdminTools\\Controllers\\Tables', 'Action' => 'IndexWithParameters'));
array_push(self::$config['Routes'], array ('Url' => self::$config['PvikAdminTools']['Url'] . 'tables/{parameters}/{preset-values}/', 'Controller' => '\\PvikAdminTools\\Controllers\\Tables', 'Action' => 'IndexWithParameters'));
array_push(self::$config['Routes'], array ('Url' => self::$config['PvikAdminTools']['Url'] . 'files/upload/', 'Controller' => '\\PvikAdminTools\\Controllers\\Files', 'Action' => 'UploadFile'));
array_push(self::$config['Routes'], array ('Url' => self::$config['PvikAdminTools']['Url'] . 'login/', 'Controller' => '\\PvikAdminTools\\Controllers\\Account', 'Action' => 'Login'));
array_push(self::$config['Routes'], array ('Url' => self::$config['PvikAdminTools']['Url'] . 'logout/', 'Controller' => '\\PvikAdminTools\\Controllers\\Account', 'Action' => 'Logout'));

