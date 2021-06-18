<?php
// require_once 'include/config.php';
// require_once 'include/utils.php';
// require_once 'Views/pattern_request.php';

// require_once "include/$cas_path/CAS.php";
// require_once "/usr/share/pear/CAS.php";
require_once "Models/Model.php";
require_once "Controllers/Controller.php";

$controllers = ['home', 'bareme'];
$controller_default = 'home';

if (isset($_GET['controller']) and in_array($_GET['controller'], $controllers)) {
    $nom_controller = $_GET['controller'];
} else {
    $nom_controller = $controller_default;
}

$nom_classe = 'Controller_' . $nom_controller;
$nom_fichier = 'Controllers/' . $nom_classe . '.php';

if (file_exists($nom_fichier)) {
    require_once $nom_fichier;
    new $nom_classe();
} else {
    die("Error 404: not found!");
}
?>
