<?php
/**
 * Basic functions for a controller in the PvikAdminTools project.
 */
abstract class PvikAdminToolsBaseController extends Controller{
    /**
     * Checks if the user is logged in.
     * @return bool 
     */
    protected function LoggedIn(){
         Core::SessionStart();
        // logged in
        if(isset($_SESSION['AdminPvikToolsLoggedIn']) && $_SESSION['AdminPvikToolsLoggedIn']  == true){
            return true;
        }
        // check if login data send
        return $this->CheckLoginData();
    }
    
    /**
     * Checks if the login data send and log user in.
     * @return bool 
     */
    protected function CheckLoginData(){
       if(Core::IsPOST('login')&&Core::IsPOST('login')&&Core::IsPOST('password')){
            // check log in data
            $LoginData = Core::$Config['PvikAdminTools']['Login'];
            if(Core::GetPOST('username')==$LoginData['Username']&&md5(Core::GetPOST('password'))==$LoginData['PasswordMD5']){
                // log in data correct
                // log in
                $_SESSION['AdminPvikToolsLoggedIn'] = true;
                return true;
            }
            else {
                // log in data false
                return false;
            }
        }
    }
    
    /**
     * Checks if the user have permission and redirects you to the login page.
     * @param bool $AutoRedirect if off the user doesn't get redirect to the log in page.
     * @return bool 
     */
    protected function CheckPermission($AutoRedirect = true){
        if(!$this->LoggedIn()){
                if($AutoRedirect){
                    // call action login
                    $this->RedirectToController('PvikAdminToolsAccount', 'Login');
                }
                return false;
        }
        return true;
    }
    
    /**
     * Searches a view in the PvikAdminTools views folder and executes it.
     * @param string $Folder 
     */
    protected function ExecuteView($Folder = '') {
        if($Folder == ''){
            $Folder = Core::$Config['PvikAdminTools']['BasePath']. 'views/';
        }
        parent::ExecuteView($Folder);
    }
    
    /**
     * Searches a view in the PvikAdminTools view folder and executes it.
     * @param string $ActionName
     * @param string $Folder 
     */
    protected function ExecuteViewByAction($ActionName, $Folder = '') {
        if($Folder == ''){
            $Folder = Core::$Config['PvikAdminTools']['BasePath']. 'views/';
        }
        parent::ExecuteViewByAction($ActionName, $Folder);
    }
    
    /**
     * Returns an array of a url parameter that is build like /param:param2:param3/
     * @param string $ParameterName
     * @return array 
     */
    protected function GetParameters($ParameterName){
        if($this->Parameters->ContainsKey($ParameterName)){
            return preg_split('/:/' ,$this->Parameters->Get($ParameterName));
        }
        return null;
    }

}
?>