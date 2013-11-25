<?php
class CurrentUser {

	private static $userRolesWeight = array('reader' => 0, 'subscriber' => 2, 'writer' => 4, 'editor' => 6, 'publisher' => 8);
	private function __construct() {
	}

	public static function init() {
		self::$userRolesWeight = array('reader' => 0, 'subscriber' => 2, 'writer' => 4, 'editor' => 6, 'publisher' => 8);
	}

	public static function getCurrentUser() {
		return $_SESSION['user'];
	}

	public static function hasWriterAccess() {
		return self::hasAccess('writer');
	}

	public static function hasEditorAccess() {
		return self::hasAccess('editor');
	}

	public static function hasPublisherAccess() {
		return self::hasAccess('publisher');
	}

	private static function hasAccess($roleToVerify) {
		if (self::$userRolesWeight[self::getCurrentUser() -> role] < self::$userRolesWeight[$roleToVerify]) {
			return false;
		} else {
			return true;
		}
	}

}
