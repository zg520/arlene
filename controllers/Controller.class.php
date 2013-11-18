<?php
abstract class Controller {
	protected $modelManager;
	protected $action;
	protected $uriParams;
	protected $viewFile;

	public function __construct($modelManager, $action, $uriParams) {
		$this -> action = $action;
		$this -> uriParams = $uriParams;
		$this -> modelManager = $modelManager;
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
