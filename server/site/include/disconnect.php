<?php
include 'config.php';

if (isset($_SESSION['connect'])) {
    unset($_SESSION['connect']);
    session_destroy();
}

header("Location: $domain");
?>