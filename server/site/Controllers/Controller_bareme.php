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
        $data = [];

        if (isset($_POST['sub'])) {
            if (($code = checkData($_POST)) == 0) {
                // TODO : verif nom TP dans projectList et creation de .bareme
                $data = [
                    'title' => 'Bareme editor',
                    'ok' => 'Le TP .bareme à été ajouté avec succès', 
                    'request' => request_render(1, 1)
                ];
            }
            else {
                $data = [
                    'title' => 'Bareme editor',
                    'error' => 'Il y a eu un problème vers : '.$code,
                    'request' => request_render(1, 1)
                ];
            }
        }
        else {
            $data = [
                'title' => 'Bareme editor',
                'error' => 'Quelque chose s\'est mal passer',
                'request' => request_render(1, 1)
            ];
        }

        $this->render('bareme', $data);
    }

    public function action_edit() {
        require 'include/config.php';
    }
}
?>
