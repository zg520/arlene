<?php
class MembersController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new MemberManager();
	}

	public function login() {
		$member = new Member();
		$member -> userId = $_POST['name'];
		$member -> password = $_POST['password'];
		$_SESSION['user'] = $this -> modelManager -> authenticateMember($member);
		$viewBag['redirectUri'] = "/";
		$this -> renderView($viewBag);
	}

	public function logout() {
		$viewBag['user'] = $_SESSION['user'];
		$viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		unregisterGlobals();
		session_destroy();
		$this -> renderView($viewBag);
	}

}
