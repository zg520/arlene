<?php
/**
 * A class providing functionality for the MembersController actions
 *
 * @package Common\Controllers
 */
class MembersController extends Controller {
	
	/**
	 * The members manager.
	 * 
	 * @access private
	 * 
	 * @var MemberManager
	 */
	private $modelManager;
	
	/**
	 * Initialises default instance of @see MembersController class.
	 * 
	 * @access public
	 */
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new MemberManager();
	}
	
	/**
	 * Implements the login action of the @see MembersController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
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
	
	/**
	 * Implements the logout action of the @see MembersController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
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
	
	/**
	 * Implements the register action of the @see MembersController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function register() {
		$this -> viewBag['user'] = $_SESSION['user'];

		unregisterGlobals();
		session_destroy();
		$this -> renderView();
	}
}
