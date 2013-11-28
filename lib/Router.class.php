<?php
class Router {

	private $uriParams;
	private $action;
	private $controllerName;

	private $roles = array('publisher', 'editor', 'subscriber', 'writer');

	function __construct() {
		if (count($_GET) == 0) {
			$this -> controllerName = 'Home';
			$this -> action = 'index';
		} else {
			$this -> uriParams = explode('/', $_GET['path']);
			$this -> controllerName = $this -> uriParams[0];
		}

		if (isset($this -> uriParams[1]) && !empty($this -> uriParams[1])) {
			$this -> action = $this -> uriParams[1];
		} else {
			$this -> action = 'index';
		}
	}

	public function route() {
		if (!file_exists(ROOT . DS . "controllers" . DS . $this -> controllerName . "Controller.class.php")) {
			return new ErrorController("badurl", $this -> uriParams);
		}
		$class = ucfirst(strtolower($this -> controllerName)) . "Controller";
		if (method_exists($class, $this -> action)) {
			$obj = new $class($this -> action, $this -> uriParams);
			if ($obj -> isAuthorized(CurrentUser::getUser() -> role)) {
				return $obj;
			} else {
				return new ErrorController("unauthorized", $this -> uriParams);
			}
		} else {
			return new ErrorController("badurl", $this -> uriParams);
		}
	}

	private function isAutorized() {
		if (currentUser() == null) {
			return false;
		} else {

		}
	}

}
