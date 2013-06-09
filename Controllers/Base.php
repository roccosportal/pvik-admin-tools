<?php
namespace PvikAdminTools\Controllers;
use \Pvik\Web\Request;
/**
 * Basic functions for a controller in the PvikAdminTools project.
 */
abstract class Base extends \Pvik\Web\Controller{
    
    /**
     * 
     * @param \Pvik\Web\Request $request
     * @param string $controllerName
     */
    public function __construct(Request $request, $controllerName) {
        parent::__construct($request, $controllerName);
        // strip out  \PvikAdminTools\Controllers\
        $this->controllerName = str_replace('\\PvikAdminTools\\Controllers\\', '', $controllerName);
    }
    /**
     * Checks if the user is logged in.
     * @return bool 
     */
    protected function loggedIn(){
        $this->request->sessionStart();
        // logged in
        if(isset($_SESSION['AdminPvikToolsLoggedIn']) && $_SESSION['AdminPvikToolsLoggedIn']  == true){
            return true;
        }
        // check if login data send
        return $this->checkLoginData();
    }
    
    /**
     * Checks if the login data send and log user in.
     * @return bool 
     */
    protected function checkLoginData(){
       if($this->request->isPOST('login')&&$this->request->isPOST('password')){
            // check log in data
            $loginData = \Pvik\Core\Config::$config['PvikAdminTools']['Login'];
            if($this->request->getPOST('username')==$loginData['Username']&&md5($this->request->getPOST('password'))==$loginData['PasswordMD5']){
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
     * @param bool $autoRedirect if off the user doesn't get redirect to the log in page.
     * @return bool 
     */
    protected function checkPermission($autoRedirect = true){
        if(!$this->loggedIn()){
                if($autoRedirect){
                    // call action login
                    $this->redirectToController('\\PvikAdminTools\\Controllers\\Account', 'Login');
                }
                return false;
        }
        return true;
    }
    
    /**
     * Searches a view in the PvikAdminTools views folder and executes it.
     * @param string $folder 
     */
    protected function executeView($folder = '') {
        if($folder == ''){
            $folder = \Pvik\Core\Config::$config['PvikAdminTools']['BasePath']. 'Views/';
        }
        parent::executeView($folder);
    }
    
    /**
     * Searches a view in the PvikAdminTools view folder and executes it.
     * @param string $actionName
     * @param string $folder 
     */
    protected function executeViewByAction($actionName, $folder = '') {
        if($folder == ''){
            $folder = \Pvik\Core\Config::$config['PvikAdminTools']['BasePath']. 'Views/';
        }
        parent::executeViewByAction($actionName, $folder);
    }
    
    /**
     * Returns an array of a url parameter that is build like /param:param2:param3/
     * @param string $parameterName
     * @return array 
     */
    protected function getParameters($parameterName){
        if($this->request->getParameters()->containsKey($parameterName)){
            return preg_split('/:/' ,$this->request->getParameters()->get($parameterName));
        }
        return null;
    }

}
