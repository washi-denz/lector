<?php

session_start();
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('APP', ROOT );

require APP . 'config.php';
require APP . 'core/application.php';
require APP . 'core/controller.php';

$app = new Application("init");

?>