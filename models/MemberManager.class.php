<?php
class MemberManager extends DataManager {

	
	public function __construct() {
		$this -> objMapper = new ObjectMapper();
	}
	public function getAllMembers(){
		$members = $this -> query("SELECT `id`, `role` FROM `users`");
		return $this -> objMapper -> toMembers($members);
	}
	public function authenticateMember($member) {
		$result = $this -> query("SELECT `id`, `role` FROM `users` WHERE `id` = ? AND `password` = ?", array($member -> userId, $member -> password));
		$member = $this -> toSingleObject($this -> objMapper -> toMembers($result));
		$member -> authenticate();
		return $member;
	}
}
