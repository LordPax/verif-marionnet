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
        require_once 'include/config.php';

        // $data = [
        //     'title' => 'Home',
        //     'name' => phpCAS::getUser()
        // ];
        // $this->render('home', $data);

        header('Location: '.$domain.'?controller=bareme');
    }
}
?>
