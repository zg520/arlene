<?php
/**
 * A class to hold member information.
 *
 * @access default
 * @package Contract
 */
class Member {

	/**
	 * The user id of the member.
	 *
	 * @access public
	 */
	public $userId;

	/**
	 * The role of the member.
	 * Used for specifying permissions in the application.
	 *
	 * @access public
	 */
	public $role;

	/**
	 * Value representing whether the current member is authenticated
	 * within the application.
	 *
	 * @access private
	 */
	private $isAuthenticated = false;

	/**
	 * Checks whether the member is authenticated
	 * within the application.
	 *
	 * @access public
	 * @return true if the member is authenticated.
	 */
	public function isAuthenticated() {
		return $this -> isAuthenticated;
	}

	/**
	 * Authenticates the member.
	 *
	 * @access public
	 * @return void.
	 */
	public function authenticate() {
		$this -> isAuthenticated = true;
	}

}
