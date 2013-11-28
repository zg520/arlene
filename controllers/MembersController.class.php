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
		try{
			$_SESSION['user'] = $this -> modelManager -> authenticateMember($member);
			setupSession();
		}catch(Exception $e){
			$this-> addNotification(new Notification('error', "Couldnt authenticate you. Try again."));
		}
		$viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($viewBag, true);
	}

	public function logout() {
		$viewBag['user'] = $_SESSION['user'];
		if (!strncmp(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH), "/admin", strlen($needle))) {
			$viewBag['redirectUri'] = "/";
		} else {
			$viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		}

		unregisterGlobals();
		session_destroy();
		$this -> renderView($viewBag, true);
	}

}
