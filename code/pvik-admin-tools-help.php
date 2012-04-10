<?php
/**
 * A helper class for mixed functions.
 */
class PvikAdminToolsHelp {
    /**
     * Returns a url safe text.
     * @param string $Text
     * @return string 
     */
    public static function MakeUrlSafe($Text){
        $String = str_replace('-', '', $Text);
        $String = str_replace(' ', '-', $String);
        $String = str_replace(':', '', $String);
        $String = str_replace('.', '', $String);
        $String = str_replace(',', '', $String);
        $String = str_replace('!', '', $String);
        $String = str_replace('?', '', $String);
        $String = str_replace("'", '', $String);
        $String = str_replace("&", '', $String);
         $String = str_replace('--', '-', $String);
        $String = str_replace('--', '-', $String);
        $String = strtolower($String);
        return $String;
    }
    
    /**
     * Returns a relative file path with the PvikAdminTools base path.
     * @param string $Path
     * @return string 
     */
    public static function FileRelativePath($Path){
        return Core::RelativePath(Core::$Config['PvikAdminTools']['BasePath'] . $Path);
    }
}
?>