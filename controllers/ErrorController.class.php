<?php
class ErrorController extends Controller {
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
	}

	public function unauthorized() {
		$this -> renderView($this -> viewBag);
	}
}
