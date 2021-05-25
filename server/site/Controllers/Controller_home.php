<?php
class Controller_home extends Controller {
    public function __construct() {
        if (isset($_GET['action']) and method_exists($this, "action_" . $_GET["action"])) {
            $action = "action_" . $_GET["action"];
            $this->$action();
        } else {
            $this->action_default();
        }
    }

    public function action_default() {
        require 'include/config.php';
        
        // phpCAS::setDebug();
        // phpCAS::setVerbose(true);

        phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context, true);
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();

        $data = [
            'title' => 'Home',
            'name' => phpCAS::getUser()
        ];
        $this->render('home', $data);
    }
}
?>
