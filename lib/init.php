<?php
/** Indicates whether this is a development environment **/
define('DEVELOPMENT_ENVIRONMENT', true);
/** Defines the name of the default database. **/
define('DB_NAME', '');
/** Defines the default user name to connect to the db. **/
define('DB_USER', '');
/** Defines the default user password to connect to the db. **/
define('DB_PASSWORD', '');
/** Defines the host name of the database. **/
define('DB_HOST', '');

/** Require the common library once. **/
require_once (ROOT . DS . 'lib' . DS . 'common.php');

/**
 * Auto loads all the php classes necessary.
 **/
function __autoload($className) {
	if (file_exists(ROOT . DS . 'lib' . DS . $className . '.class.php')) {
		require_once (ROOT . DS . 'lib' . DS . $className . '.class.php');
	} else if (file_exists(ROOT . DS . 'controllers' . DS . $className . '.class.php')) {
		require_once (ROOT . DS . 'controllers' . DS . $className . '.class.php');
	} else if (file_exists(ROOT . DS . 'models' . DS . $className . '.class.php')) {
		require_once (ROOT . DS . 'models' . DS . $className . '.class.php');
	} else {
		error_log('Couldnt load class: ' . $className);
	}
}
