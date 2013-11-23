<?php
abstract class Controller {
	protected $action;
	protected $uriParams;
	protected $viewFile;
	protected $viewBag;
	
	public function __construct( $action, $uriParams) {
		$this -> action = $action;
		$this -> uriParams = $uriParams;
		$this -> viewBag = array();
	}

	public function execute() {
		$this -> {$this->action}();
	}

	protected function renderView($viewBag, $isPartial = false) {
		$controllerName = get_class($this);
		$this -> viewFile = ROOT . DS . 'views' . DS . str_replace('Controller', '', $controllerName) . DS . $this -> action . '.php';
		if ($isPartial) {
			require ($this -> viewFile);
		} else {
			require (ROOT . DS . 'views' . DS . 'SharedLayout.php');
		}
	}

}
