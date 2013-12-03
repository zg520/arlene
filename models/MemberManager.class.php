<?php
/**
 * A class providing methods to work with member objects.
 *
 * @package Common\Model\Managers
 */
class MemberManager extends DataManager {

	/**
	 * Initialises an instance of the @see MemberManager.
	 */
	public function __construct() {
		$this -> objMapper = new ObjectMapper();
	}
	
	/**
	 * Fetches all members.
	 * 
	 * @access public 
	 * 
	 * @return array The members of the application.
	 */
	public function getAllMembers(){
		$members = $this -> query("SELECT `id`, `role` FROM `users`");
		return $this -> objMapper -> toMembers($members);
	}
	
	/**
	 * Authenticates the member.
	 * 
	 * @access public 
	 * 
	 * @param string $id The member id.
	 * @param string $password The member password.
	 * 
	 * @return Member The member if found, false otherwise.
	 */
	public function authenticateMember($id, $password) {
		$result = $this -> query("SELECT `id` as `user_id`, `role` FROM `users` WHERE `id` = ? AND `password` = ?", array($id, $password));
		if(count($result[0]) > 0){ 
			$member = $this -> toSingleObject($this -> objMapper -> toMembers($result));
			$member -> authenticate();
			return $member;
		}
		return false;
	}
	
	/**
	 * Updates a member.
	 * 
	 * @access public 
	 * 
	 * @param string $id The member id.
	 * @param string $role The member role.
	 * 
	 * @return bool True if the operation was successful, false otherwise.
	 */
	public function updateMemberRole($id, $role){
		$result = $this -> upsert("UPDATE `users` SET `role` =:role  WHERE `id` = :id", array('id' => $id ,'role' => $role ));
		if($result){
			return true;
		}
		return false;
	}
}
