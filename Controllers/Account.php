<?php
namespace PvikAdminTools\Controllers;
/**
 * Logic for login and logout.
 */
class Account extends Base {
    /**
     * The logic for login.
     */
    public function loginAction(){
        // checks if already logged in or login data send
        // turn off auto redirect
        if($this->checkPermission(false)){
            // if already logged in redirect to admin root
            $this->redirectToPath('~' . \Pvik\Core\Config::$config['PvikAdminTools']['Url']);
        }
        elseif($this->request->isPOST('login')&&$this->request->isPOST('username')&&$this->request->isPOST('password')){
            // log in data sended but were wrong
             $this->viewData->set('Username',$this->request->getPOST('username'));
             $this->viewData->set('Error', true);
             $this->executeView();
        }
        else {
            // show view to log in
             $this->executeView();
        }
    }
    /**
     * The logic for logout.
     */
    public function logoutAction(){
        $this->request->sessionStart();
        unset($_SESSION['AdminPvikToolsLoggedIn']);
        // redirect
        $this->redirectToPath('~/');
    }
}
