<?php
 define('DS', DIRECTORY_SEPARATOR);
 define('ROOT', dirname(__FILE__));

 include_once(ROOT . DS . 'lib' . DS . 'init.php');
 
initLogging();
removeMagicQuotes();
unregisterGlobals();
setupSession();

$router = new Router();
$controller = $router->route();
$controller->execute();