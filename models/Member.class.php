<?php
class Member {
	public $userId;
	public $role;
	public $password;
	private $isAuthenticated = false;

	public function isAuthenticated() {
		return $this -> isAuthenticated;
	}

}
