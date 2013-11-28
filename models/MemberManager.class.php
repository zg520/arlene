<?php
class MemberManager extends DataManager {
	/**
	 * Mapping rules for creating Member objects.
	 *
	 * @var array
	 */
	public function __construct() {
		$this -> objMapper = new ObjectMapper();
	}

	public function authenticateMember($member) {
		$result = $this -> query("SELECT `id`, `role` FROM `users` WHERE `id` = ? AND `password` = ?", array($member -> userId, $member -> password));
		$member = $this -> toSingleObject($this -> objMapper -> toMembers($result));
		$member -> authenticate();
		return $member;
	}
}
