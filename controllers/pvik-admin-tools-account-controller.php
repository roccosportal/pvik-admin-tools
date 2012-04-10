<?php
/**
 * Logic for login and logout.
 */
class PvikAdminToolsAccountController extends PvikAdminToolsBaseController {
    /**
     * The logic for login.
     */
    public function Login(){
        // checks if already logged in or login data send
        // turn off auto redirect
        if($this->CheckPermission(false)){
            // if already logged in redirect to admin root
            $this->RedirectToPath('~' . Core::$Config['PvikAdminTools']['Url']);
        }
        elseif(Core::IsPOST('login')&&Core::IsPOST('username')&&Core::IsPOST('password')){
            // log in data sended but were wrong
             $this->ViewData->Set('Username', Core::GetPOST('username'));
             $this->ViewData->Set('ErrorMessage', 'Username or password wrong');
             $this->ExecuteView();
        }
        else {
            // show view to log in
             $this->ExecuteView();
        }
    }
    /**
     * The logic for logout.
     */
    public function Logout(){
        Core::SessionStart();
        unset($_SESSION['AdminPvikToolsLoggedIn']);
        // redirect
        $this->RedirectToPath('~/');
    }
}
?>