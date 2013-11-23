<?php
class HomeController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new ArticleManager();
	}

	public function index() {
		$viewBag = array();
		$viewBag['recommended'] = $this -> modelManager -> getRecommended();
		$viewBag['newest'] = $this -> modelManager -> getNewest();
		$viewBag['popular'] = $this -> modelManager -> getRecommended();
		$this -> renderView($viewBag);
	}

}
