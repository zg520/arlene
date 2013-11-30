<?php
class HomeController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new ArticleManager();
	}

	public function index() {
		$this -> viewBag['recommended'] = $this -> modelManager -> getRecommended(5, 0);
		$this -> viewBag['newest'] = $this -> modelManager -> getNewest(5, 0);
		$this -> viewBag['popular'] = $this -> modelManager -> getRecommended(5, 0);
		$this -> renderView();
	}

}
