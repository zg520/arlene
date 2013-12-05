<?php

/** Check if environment is development and display errors **/

function initLogging() {
	if (DEVELOPMENT_ENVIRONMENT == true) {
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
	} else {
		error_reporting(E_ALL);
		ini_set('display_errors', 'Off');
		ini_set('log_errors', 'On');
		/*ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . com_create_guid() . '.log');*/
	}
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}


function userRoleToInt($roleOne) {
	if ($roleOne == "reader")
		return 0;
	else if ($roleOne == "subscriber")
		return 2;
	else if ($roleOne == "writer")
		return 4;
	else if ($roleOne == "editor")
		return 6;
	else if ($roleOne == "publisher")
		return 8;
}

function removeMagicQuotes() {
	if (get_magic_quotes_gpc()) {
		$_GET = stripSlashesDeep($_GET);
		$_POST = stripSlashesDeep($_POST);
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

/** Check register globals and remove them **/

function unregisterGlobals() {
	if (ini_get('register_globals')) {
		$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
		foreach ($array as $value) {
			foreach ($GLOBALS[$value] as $key => $var) {
				if ($var === $GLOBALS[$key]) {
					unset($GLOBALS[$key]);
				}
			}
		}
	}
}

function getNotification() {
	return $_SESSION['notifications'] -> dequeue();
}

function notificationsExist() {
	return !$_SESSION['notifications'] -> isEmpty();
}

function setupSession() {
	if(session_status() == PHP_SESSION_NONE){
	session_start();
	CurrentUser::init();
	if (!isset($_SESSION['user'])) {
		$_SESSION['user'] = new Member();
		$_SESSION['user'] -> userId = "anonymous";
		$_SESSION['user'] -> role = "reader";
	}
	if(empty($_SESSION['notifications'])){
		$_SESSION['notifications'] = new SplQueue();
	}
	}
}
