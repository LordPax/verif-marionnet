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

    public function action_create() {
        require 'include/config.php';
        require 'include/utils.php';

        if (checkData($_POST) == 0) {
            $this->action_default();
            // $this->render('test', [
            //     'title' => 'Bareme editor',
            //     'dump' =>  
            // ]);
        }
        else
            $this->action_error('il y a eu un problème');
    }

    public function action_edit() {
        require 'include/config.php';
    }
}
?>
