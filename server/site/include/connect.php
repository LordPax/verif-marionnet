<?php
// require_once 'config.php';

phpCAS::setDebug();
phpCAS::setVerbose(true);

phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context, true);
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();

/* echo var_dump(phpCAS::getAttributes()['eduPersonAffiliation']); */

/* if (preg_match('/^[0-9]+/', phpCAS::getUser())) { */
if (preg_match('/teacher/', phpCAS::getAttributes()['eduPersonAffiliation']) || preg_match('/11801904/', phpCAS::getUser())) {
    $username = phpCAS::getUser();
    $projectDirUser = $projectDir.'/'.$username;
    if (!is_dir($projectDirUser))
        mkdir($projectDirUser);
}
else 
    phpCAS::logoutWithUrl($domain);



?>
