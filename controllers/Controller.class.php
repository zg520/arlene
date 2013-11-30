<?php
abstract class Controller {
	protected $action;
	protected $uriParams;
	protected $viewFile;
	protected $viewBag;
	protected $authorizationMapping = array();

	public function __construct($action, $uriParams) {
		$this -> action = $action;
		$this -> uriParams = $uriParams;
		$this -> viewBag = array();
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
	}

	public function execute() {
		$this -> {$this->action}();
	}

	public function isAuthorized($userRole) {
		if (count($this -> authorizationMapping) < 1) {
			return true;
		} else {
			if (isset($this -> authorizationMapping[strtolower($this -> action)])) {
				return userRoleToInt($this -> authorizationMapping[$this -> action]) <= userRoleToInt($userRole) ? true : false;
			}
			else{
				return false;
			}
		}
	}

	protected function addNotification($type, $text) {
		$_SESSION['notifications'] -> enqueue(new Notification($type, $text));
	}

	protected function renderView($viewBag, $isRedirect = false) {
		$controllerName = get_class($this);
		$this -> viewFile = ROOT . DS . 'views' . DS . str_replace('Controller', '', $controllerName) . DS . $this -> action . '.php';
		if ($isRedirect) {
			require (ROOT . DS . 'views' . DS . 'SharedRedirect.php');
		} else {
			require (ROOT . DS . 'views' . DS . 'SharedLayout.php');
		}
	}

}
