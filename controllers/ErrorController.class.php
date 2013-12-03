<?php
/**
 * A class providing functionality for the ErrorController actions
 *
 * @package Common\Controllers
 */
class ErrorController extends Controller {
	
	/**
	 * Initialises default instance of @see ErrorController class.
	 * 
	 * @access public
	 */
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
	}

	/**
	 * Implements the unauthorized action of the @see ErrorController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function unauthorized() {
		$this -> renderView();
	}
	
	/**
	 * Implements the badUrl action of the @see ErrorController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function badUrl(){
		$this -> renderView();
	}
}
