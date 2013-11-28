<?php
class Utility {

	private function __construct() {
	}

	public static function generateArticleId($title) {
		$result;

		if (preg_match_all("/(\w)*/", $title, $result)) {
			$res = array_filter($result[0], function($item) {
				return !empty($item[0]);
			});
			return implode("-", $res);
		}
		return null;
	}
}
