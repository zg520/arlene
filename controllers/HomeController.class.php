<?php
class HomeController extends Controller {
	public function __construct($action, $uriParams) {
		parent::__construct(new ArticleManager(), $action, $uriParams);
	}

	public function index() {
		$viewBag = array();
		$viewBag['recommended'] = $this -> modelManager -> getRecommended();
		$viewBag['newest'] = $this -> modelManager -> getAll();
		$viewBag['popular'] = $this -> modelManager -> getRecommended();
		$this -> renderView($viewBag);
	}

}