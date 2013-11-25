<?php
class HomeController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new ArticleManager();
	}

	public function index() {
		$viewBag = array();
		$viewBag['recommended'] = $this -> modelManager -> getRecommended(5, 0);
		$viewBag['newest'] = $this -> modelManager -> getNewest(5, 0);
		$viewBag['popular'] = $this -> modelManager -> getRecommended(5, 0);
		$this -> renderView($viewBag);
	}

}
