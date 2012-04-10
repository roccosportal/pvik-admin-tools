<?php
self::$Config['PvikAdminTools'] = array ();
// gets and sets the base path of PvikAdminTools
self::$Config['PvikAdminTools']['BasePath'] = '~/' . str_replace('load.php', '', str_replace(getcwd(), '',  realpath ( __FILE__ ))) . '/';
//include pvik-admin-tools config
require(Core::RealPath(self::$Config['PvikAdminTools']['BasePath'] .'configs/config.php'));

// add include files
if(!isset(self::$Config['IncludeFolders'])){
    self::$Config['IncludeFolders'] = array();
}
array_push(self::$Config['IncludeFolders'], self::$Config['PvikAdminTools']['BasePath'] .'code/');
array_push(self::$Config['IncludeFolders'], self::$Config['PvikAdminTools']['BasePath'] .'controllers/');

// add routes
if(!isset(self::$Config['Routes'])){
    self::$Config['Routes'] = array();
}
array_push(self::$Config['Routes'], array ('Url' => self::$Config['PvikAdminTools']['Url'] , 'Controller' => 'PvikAdminToolsTables', 'Action' => 'Index'));
array_push(self::$Config['Routes'], array ('Url' => self::$Config['PvikAdminTools']['Url'] . 'tables/', 'Controller' => 'PvikAdminToolsTables', 'Action' => 'Index'));
array_push(self::$Config['Routes'], array ('Url' => self::$Config['PvikAdminTools']['Url'] . 'tables/{parameters}/', 'Controller' => 'PvikAdminToolsTables', 'Action' => 'IndexWithParameters'));
array_push(self::$Config['Routes'], array ('Url' => self::$Config['PvikAdminTools']['Url'] . 'tables/{parameters}/{preset-values}/', 'Controller' => 'PvikAdminToolsTables', 'Action' => 'IndexWithParameters'));
array_push(self::$Config['Routes'], array ('Url' => self::$Config['PvikAdminTools']['Url'] . 'files/upload/', 'Controller' => 'PvikAdminToolsFiles', 'Action' => 'UploadFile'));
array_push(self::$Config['Routes'], array ('Url' => self::$Config['PvikAdminTools']['Url'] . 'login/', 'Controller' => 'PvikAdminToolsAccount', 'Action' => 'Login'));
array_push(self::$Config['Routes'], array ('Url' => self::$Config['PvikAdminTools']['Url'] . 'logout/', 'Controller' => 'PvikAdminToolsAccount', 'Action' => 'Logout'));

?>
