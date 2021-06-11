<?php
// require_once 'config.php';

phpCAS::setDebug();
phpCAS::setVerbose(true);

phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context, true);
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();

// echo var_dump(phpCAS::getAttributes());

// if (preg_match('/^[0-9]+/', phpCAS::getUser())) {
//     phpCAS::logoutWithUrl($domain);
// }
?>
