<?php

/** 
 * Setup logging based on whether the environment is dev or prod.
 * @return void
 */
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

/**
 * Check for Magic Quotes and remove them.
 * 
 * @return string Stripped down version of the string.
 */

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

/**
 * Helper function used for comparing user roles.
 * 
 * @deprecated Should be deprecated.
 * 
 * @return int the user role weight.
 */
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

/**
 * Remove the magic quotes from GET, POST, COOKIE.
 * 
 * @return void
 */
function removeMagicQuotes() {
	if (get_magic_quotes_gpc()) {
		$_GET = stripSlashesDeep($_GET);
		$_POST = stripSlashesDeep($_POST);
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

/** 
 * Empty all the global variables.
 * 
 * @return void 
 */
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

/**
 * Get the oldest notification and remove it from the queue.
 * 
 * @return Notification the first notification to be show to the user.
 */
function getNotification() {
	return $_SESSION['notifications'] -> dequeue();
}

/**
 * Check if any notifications exists in the queue.
 * 
 * @return bool True if there is any, false otherwise.
 */
function notificationsExist() {
	return !$_SESSION['notifications'] -> isEmpty();
}

/**
 * Setup a new user session
 * @return void
 */
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
