<?php
/**
 * A class providing base functionality for all Controllers.
 *
 * @package Common\Controllers
 * @abstract
 */
abstract class Controller {
	
	/**
	 * The action requested by the user.
	 * 
	 * @access protected
	 * 
	 * @var string
	 */
	protected $action;
	 
	 /**
	 * The request parameters after the controller/action/ .
	 * 
	 * @access protected
	 * 
	 * @var array
	 */
	protected $uriParams;
	/**
	 * The view file location.
	 * 
	 * @access protected
	 * 
	 * @var string
	 */
	protected $viewFile;
	
	/**
	 * The data to be displayed to in the view.
	 * 
	 * @access protected
	 * 
	 * @var array
	 */
	protected $viewBag;
	
	/**
	 * The authorization rules.
	 * 
	 * @access protected
	 * 
	 * @var array
	 */
	protected $authorizationMapping = array();

	/**
	 * Initialises a default instance of the @see Controller class.
	 * 
	 * @access public
	 * 
	 * @param string $action The action from the user request
	 * @param array $uriParams The parameters after the user action.
	 * 
	 * @return void
	 */
	public function __construct($action, $uriParams) {
		$this -> action = $action;
		$this -> uriParams = $uriParams;
		$this -> viewBag = array();
	}
	
	/**
	 * Executes the action specified for the current controller.
	 * 
	 * @return void
	 */
	public function execute() {
		$this -> {$this->action}();
	}
	
	/**
	 * Verifies whether the user is authorised to execute the current controller action.
	 * 
	 * @param string $userRole The current user role.
	 * 
	 * @return bool True if the user is allowed to execute the action, false otherwise.
	 */
	public function isAuthorized($userRole) {
		if (count($this -> authorizationMapping) < 1) {
			return true;
		} else {
			if (isset($this -> authorizationMapping[strtolower($this -> action)])) {
				return userRoleToInt($this -> authorizationMapping[strtolower($this -> action)]) <= userRoleToInt($userRole) ? true : false;
			}
			else{
				return false;
			}
		}
	}
	
	/**
	 * Adds a notification to be displayed to the user.
	 * Only views requiring 'SharedNotifications.php' can display notifications.
	 * 
	 * @access protected 
	 * 
	 * @param string $type The type of the notification(info, error).
	 * @param string $text The text to be displayed.
	 * 
	 * @return void
	 */
	protected function addNotification($type, $text) {
		$_SESSION['notifications'] -> enqueue(new Notification($type, $text));
	}
	
	/**
	 * Renders the view associated with the current controller.
	 * Can also redirect or use a custom view.
	 * 
	 * @access protected
	 * 
	 * @param bool $isRedirect Optional with default value of false. Specifies whether the render should be a redirect.
	 * @param obj $customViewFile Optional with default value of dalse. Specifies a custom view file.
	 * 
	 * @return void
	 */
	protected function renderView($isRedirect = false, $customViewFile = false) {
		if(empty($customViewFile)){
			$controllerName = get_class($this);
			$this -> viewFile = ROOT . DS . 'views' . DS . str_replace('Controller', '', $controllerName) . DS . $this -> action . '.php';
		}else{
			$this -> viewFile =$customViewFile;
		}
		
		if ($isRedirect) {
			if(empty($this -> viewBag['redirectUri'])){
				$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
			}
			require (ROOT . DS . 'views' . DS . 'SharedRedirect.php');
		} else {
			require (ROOT . DS . 'views' . DS . 'SharedLayout.php');
		}
	}

}
