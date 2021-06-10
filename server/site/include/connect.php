<?php
// require_once 'config.php';

phpCAS::setDebug();
phpCAS::setVerbose(true);

phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context, true);
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();
?>
