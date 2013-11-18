<?php
class View {

	protected $viewFile;

	public function __construct($controllerClass, $action) {
		$controllerName = str_replace("Controller", "", $controllerClass);
		$this -> viewFile = ROOT . DS . "views" . DS . $controllerName . "View.php";
	}

	public function output($viewModel, $template = "MainTemplate") {
		$templateFile = ROOT . DS . "views" . DS . $template . ".php";
		if (file_exists($this -> viewFile)) {
			if ($template) {
				//include the full template
				if (file_exists($templateFile)) {
					require ($templateFile);
				} else {
					require ("views/error/badtemplate.php");
				}
			} else {
				//we're not using a template view so just output the method's view directly
				require ($this -> viewFile);
			}
		} else {
			require ("views/error/badview.php");
		}
	}

}
