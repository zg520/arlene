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
	}

	public function execute() {
		$this -> {$this->action}();
	}

	public function isAuthorized($userRole) {
		if (count($this -> authorizationMapping) < 1) {
			return true;
		} else {
			if (isset($this -> authorizationMapping[$this -> action])) {
				return userRoleToInt($this -> authorizationMapping[$this -> action]) <= userRoleToInt($userRole) ? true : false;
			}
		}
	}

	protected function addNotification(Notification $notification) {
		$_SESSION['notifications'] -> enqueue($notification);
	}

	protected function renderView($viewBag, $isPartial = true) {
		$controllerName = get_class($this);
		$this -> viewFile = ROOT . DS . 'views' . DS . str_replace('Controller', '', $controllerName) . DS . $this -> action . '.php';
		if ($isPartial) {
			require (ROOT . DS . 'views' . DS . 'SharedLayout.php');
		} else {
			require ($this -> viewFile);
		}
	}

}
