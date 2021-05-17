<?php
include 'config.php';
// temporaire le temps de pouvoir faire une authentication CAS
if (isset($_POST['sub'])) {
    if (!empty($_POST['login']) && !empty($_POST['pass'])) {
        $login = htmlspecialchars($_POST['login']);
        $pass = htmlspecialchars($_POST['pass']);

        if ($login === $loginTemp && $pass === $passTemp) {
            // session_start();
            $_SESSION['connect'] = true;
            header("Location: $domain/baremePage.php");
        }
    }
}
header("Location: $domain");
?>