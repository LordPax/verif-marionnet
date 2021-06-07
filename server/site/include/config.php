<?php
// temporaire le temps de pouvoir faire une authentication CAS
// session_start();
// $loginTemp = 'lordpax';
// $passTemp = 'azerty';

// $racine = 'http://www-info.iutv.univ-paris13.fr/';
$racine = 'http://localhost/';
// $domain = $racine.'/~gauthier/site/';
$domain = $racine.'/server/site/';
$projectDir = '/srv/http/server/exo_de_test/';
// $projectDir = '/var/www/html/verifMario.d/';
// $projectDir2 = $racine.'/verifMario.d/';
$projectListName = $projectDir.'/.projectList';
$idSsh = $projectDir.'/.id_rsa_marionnet';

$cas_path='phpCAS-1.3.6';
$cas_host='cas.univ-paris13.fr';
$cas_port=443;
$cas_context='/cas/';
?>
