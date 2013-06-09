<?php
namespace PvikAdminTools\Library;
/**
 * A class that handles registeres files.
 */
class FileRegister {
    /**
     * Contains the file paths.
     * @var array 
     */
    protected static $files;
    
    /**
     * Registers a path.
     * @param string $path 
     */
    public static function registerFile($path){
        if(!is_array(self::$files)){
            self::$files = array();
        }
        array_push(self::$files, $path);
    }
    
    /**
     * checks if a file is already registerd.
     * @param string $path
     * @return bool 
     */
    public static function isFileRegisterd($path){
        if(!is_array(self::$files)){
            self::$files = array();
        }
        
        if(in_array($path, self::$files)){
            return true;
        }
        else {
            return false;
        }
    }
}
