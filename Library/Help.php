<?php
namespace PvikAdminTools\Library;
/**
 * A helper class for mixed functions.
 */
class Help {
    /**
     * Returns a url safe text.
     * @param string $text
     * @return string 
     */
    public static function makeUrlSafe($text){
        $string = str_replace('-', '', $text);
        $string = str_replace(' ', '-', $string);
        $string = str_replace(':', '', $string);
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '', $string);
        $string = str_replace('!', '', $string);
        $string = str_replace('?', '', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace("&", '', $string);
         $string = str_replace('--', '-', $string);
        $string = str_replace('--', '-', $string);
        $string = strtolower($string);
        return $string;
    }
    
    /**
     * Returns a relative file path with the PvikAdminTools base path.
     * @param string $path
     * @return string 
     */
    public static function fileRelativePath($path){
        return \Pvik\Core\Path::relativePath(\Pvik\Core\Config::$config['PvikAdminTools']['BasePath'] . $path);
    }
}
