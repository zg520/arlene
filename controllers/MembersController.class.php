<?php
class MembersController extends Controller {
	public function __construct($action, $uriParams) {
		parent::__construct(new MemberManager(), $action, $uriParams);
	}

	public function login() {
		$viewBag = array();
		$member = new Member();
		$member -> userId = $_POST['name'];
		$member -> password =  $_POST['password'];
		$_SESSION['user'] = $this -> modelManager -> authenticateMember($member);
		$viewBag['user'] = $_SESSION['user'];
		$this -> renderView($viewBag);
	}

}
