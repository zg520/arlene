<?php
class MemberManager extends DataManager {

	
	public function __construct() {
		$this -> objMapper = new ObjectMapper();
	}
	public function getAllMembers(){
		$members = $this -> query("SELECT `id`, `role` FROM `users`");
		return $this -> objMapper -> toMembers($members);
	}
	public function authenticateMember($id, $password) {
		$result = $this -> query("SELECT `id` as `user_id`, `role` FROM `users` WHERE `id` = ? AND `password` = ?", array($id, $password));
		if(count($result[0]) > 0){ 
			$member = $this -> toSingleObject($this -> objMapper -> toMembers($result));
			$member -> authenticate();
			return $member;
		}
		return false;
	}
	public function updateMemberRole($id, $role, $except = null){
		$result = $this -> upsert("UPDATE `users` SET `role` =:role  WHERE `id` = :id", array('id' => $id ,'role' => $role ));
		if($result){
			return true;
		}
		return false;
	}
}
