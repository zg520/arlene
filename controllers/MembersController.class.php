<?php
class MembersController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new MemberManager();
	}

	public function login() {
		try {
			$member = $this -> modelManager -> authenticateMember($_POST['name'], $_POST['password']);
			if (!empty($member)) {
				$_SESSION['user'] = $member;
			}
		} catch(Exception $e) {
			$this -> addNotification('error', "Couldnt authenticate you. Try again.");
		}
		$this -> renderView(true);
	}

	public function logout() {
		$this -> viewBag['user'] = $_SESSION['user'];
		if (!strncmp(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH), "/admin", strlen($needle))) {
			$this -> viewBag['redirectUri'] = "/";
		} else {
			$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		}

		unregisterGlobals();
		session_destroy();
		$this -> renderView(true);
	}

}
