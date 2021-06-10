<?php
require_once 'config.php';

// if (isset($_SESSION['connect'])) {
//     unset($_SESSION['connect']);
//     session_destroy();
// }

phpCas::logout();

header("Location: $domain");
?>
