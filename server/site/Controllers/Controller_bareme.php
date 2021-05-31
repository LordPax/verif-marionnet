<?php
class Controller_bareme extends Controller {
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
        require 'include/utils.php';
        
        $data = [
            'title' => 'Bareme editor',
            'request' => request_render(1, 1)
        ];
        $this->render('bareme', $data);
    }

    public function action_edit() {
        require 'include/config.php';
        
        $data = [
            'title' => 'Bareme editor',
            'dump' => var_dump($_POST) 
        ];
        $this->render('test', $data);
    }
}
?>
