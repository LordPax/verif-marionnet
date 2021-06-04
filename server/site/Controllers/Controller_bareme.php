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
        require 'Views/pattern_request.php';
        
        $data = [
            'title' => 'Bareme editor',
            'request' => request_render(1)
        ];
        $this->render('bareme', $data);
    }

    public function action_create() {
        require 'include/config.php';
        require 'include/utils.php';
        require 'Views/pattern_request.php';
        $data = [];
        $projectList = [];
        $resultCheck = null;

        if (isset($_POST['sub'])) {
            $resultCheck = checkData($_POST);

            if (gettype($resultCheck) === 'array') {
                $projectList = preg_split('/\s+/', file_get_contents($projectListName));

                if (!in_array($resultCheck['TP-name'], $projectList)) {
                    $bareme = data2json($resultCheck);
                    $request = extractRequestFromJson($bareme);
                    $projectName = $projectDir.'/'.$resultCheck['TP-name'];

                    if (mkdir($projectName)) {
                        file_put_contents($projectListName, $resultCheck['TP-name']."\n", FILE_APPEND);
                        file_put_contents($projectName.'/'.'.bareme.json', $bareme);
                        file_put_contents($projectName.'/'.'.requetes.json', $request);

                        $data = [
                            'title' => 'Bareme editor',
                            'ok' => 'Le TP .bareme à été ajouté avec succès', 
                            'request' => request_render(1)
                        ];
                    }
                    else {
                        $data = [
                            'title' => 'Bareme editor',
                            'error' => 'le TP n\'a pas pue être créé',
                            'request' => generateRequest($_POST)
                        ];
                    }
                }
                else {
                    $data = [
                        'title' => 'Bareme editor',
                        'error' => 'Le TP '.$resultCheck['TP-name'].' existe déjà',
                        'request' => generateRequest($_POST)
                    ];
                }
            }
            else {
                $data = [
                    'title' => 'Bareme editor',
                    'error' => 'Il y a eu un problème vers : '.$resultCheck,
                    'request' => generateRequest($_POST)
                ];
            }
        }
        else {
            $data = [
                'title' => 'Bareme editor',
                'error' => 'Quelque chose s\'est mal passer',
                'request' => request_render(1)
            ];
        }

        $this->render('bareme', $data);
    }

    public function action_edit() {
        require 'include/config.php';
    }
}
?>
