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
        // header('Location: '.$domain.'?controller=bareme&action=formCreate');
        require_once 'include/config.php';
        require_once 'include/utils.php';
        require_once 'Views/pattern_request.php';

        $projectList = preg_split('/\s+/', file_get_contents($projectListName));
        $projectList = array_filter($projectList, function($elem) {
            return preg_match('%^'.phpCAS::getUser().'/.+$%', $elem);
        });

        $this->render('choice', [
            'title' => 'Menu',
            'projectList' => $projectList
        ]);
    }

    public function action_formCreate(array $prevData = null) {
        require_once 'include/config.php';
        require_once 'include/utils.php';
        require_once 'Views/pattern_request.php';
        
        $data = [
            'title' => 'Bareme editor',
            'form' => '?controller=bareme&action=create',
            'request' => request_render(1)
        ];
        if (isset($prevData['error'])) $data['error'] = $prevData['error'];
        $this->render('bareme', $data);
    }

    public function action_create() {
        $this->upload(false); // false pour create
    }

    public function action_formEdit(array $prevData = null) {
        require_once 'include/config.php';
        require_once 'include/utils.php';
        require_once 'Views/pattern_request.php';
        $projectList = [];

        if (isset($_GET['tpName'])) {
            $tpName = e($_GET['tpName']);
            $projectList = preg_split('/\s+/', file_get_contents($projectListName));
            
            if (in_array($tpName, $projectList) && $tpName !== '') {
                $data = json2data(file_get_contents($projectDir.'/'.$tpName.'/.bareme.json'));
                $this->render('bareme', [
                    'title' => 'Bareme editor',
                    'form' => '?controller=bareme&action=edit',
                    'TPname' => $tpName,
                    'tolerance' => $data['tolerance'],
                    'request' => generateRequest($data)
                ]);
            }
            else {
                header('Location: '.$domain.'?controller=bareme');
            }
        }
        else {
            header('Location: '.$domain.'?controller=bareme');
        }
    }

    public function action_edit() {
        $this->upload(true); // true pour edit
    }

    /**
     * @param mode false pour mode create et true pour mode edit
     */
    private function upload(bool $mode) {
        require_once 'include/config.php';
        require_once 'include/utils.php';
        require_once 'Views/pattern_request.php';
        $data = [];
        $projectList = [];
        $resultCheck = null;

        if (isset($_POST['sub'])) {
            /* $verifFile = preg_match('/^[A-Za-z0-9\-_]+\.mar$/', $_FILES['file']['name']); */
            // si on est en mode create on v??rifie quoi qu'il arrive et si on est en mode edit on v??rifie seulement si le nom n'est pas vide (permet de ne pas ??tre oblig?? de mettre un fichier alors qu'on je veut pas le modifier) 
            // if ((!$mode && $verifFile) || ($mode && (isset($_FILE['file']['name']) ? $verifFile : true))) {
            if (preg_match('/^[A-Za-z0-9\-_]+\.mar$/', $_FILES['file']['name'])) {
                $resultCheck = checkData($_POST);

                if (gettype($resultCheck) === 'array') {
                    $projectList = preg_split('/\s+/', file_get_contents($projectListName));
                    $TPname = $mode ? $resultCheck['TP-name'] : $username.'/'.$resultCheck['TP-name']; 
                    $projectName = $projectDir.'/'.$TPname.'/';
                    $marFile = explode('/', $TPname)[1].'.mar';

                    if (in_array($TPname, $projectList) === $mode) {
                        $bareme = data2json($resultCheck);
                        $request = extractRequestFromJson($bareme);

                        if (!is_dir($projectName)) mkdir($projectName);

                        if (is_dir($projectName)) {
                            if (!$mode) // si on est en mode create
                                file_put_contents($projectListName, $TPname."\n", FILE_APPEND);
                            file_put_contents($projectName.'/.bareme.json', $bareme);
                            file_put_contents($projectName.'/.requetes.json', $request);
                            // if (isset($_FILE['file']))
                            move_uploaded_file($_FILES['file']['tmp_name'], $projectName.'/'.$marFile);

                            $ok = $mode ? 'Le TP .bareme ?? ??t?? modifi?? avec succ??s ' : 'Le TP .bareme ?? ??t?? ajout?? avec succ??s '; 
                            $form = '?controller=bareme&action=create';
                            $data = [
                                'ok' => $ok,
                                'request' => request_render(1)
                            ];
                        }
                        else {
                            $form = $mode ? '?controller=bareme&action=edit' : '?controller=bareme&action=create';
                            $data = [
                                'error' => 'le TP n\'a pas pue ??tre cr????',
                                'TPname' => $_POST['TP-name'],
                                'tolerance' => $_POST['tolerance'],
                                'request' => generateRequest($_POST)
                            ];
                        }
                    }
                    else {
                        /* echo $projectName; */
                        $error = $mode ? 'Le TP '.$resultCheck['TP-name'].' n\'existe pas' : 'Le TP '.$resultCheck['TP-name'].' existe d??j??'; 
                        $form = $mode ? '?controller=bareme&action=edit' : '?controller=bareme&action=create';
                        $data = [
                            'error' => $error,
                            'TPname' => $_POST['TP-name'],
                            'tolerance' => $_POST['tolerance'],
                            'request' => generateRequest($_POST)
                        ];
                    }
                }
                else {
                    $form = $mode ? '?controller=bareme&action=edit' : '?controller=bareme&action=create';
                    $data = [
                        'error' => 'Il y a eu un probl??me vers : '.$resultCheck,
                        'TPname' => $_POST['TP-name'],
                        'tolerance' => $_POST['tolerance'],
                        'request' => generateRequest($_POST)
                    ];
                }
            }
            else {
                $form = $mode ? '?controller=bareme&action=edit' : '?controller=bareme&action=create';
                $data = [
                    'error' => 'Le format du fichier .mar ne correspond pas',
                    'TPname' => $_POST['TP-name'],
                    'tolerance' => $_POST['tolerance'],
                    'request' => generateRequest($_POST)
                ];
            }
        }
        else {
            $form = $mode ? '?controller=bareme&action=edit' : '?controller=bareme&action=create';
            $data = [
                'request' => request_render(1)
            ];
        }

        $data['title'] = 'Bareme editor';
        $data['form'] = $form;

        $this->render('bareme', $data);
    }
}
?>
