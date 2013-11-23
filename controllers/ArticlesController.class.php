<?php
class ArticlesController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new ArticleManager();
	}

	public function getById() {
		$viewBag = array();
		$viewBag['article'] = $this -> modelManager -> getById($this -> uriParams[2]);

		$this -> renderView($viewBag);
	}

	public function index() {
		$viewBag = array();
		$viewBag['all'] = $this -> modelManager -> getById();
		$this -> renderView($viewBag);
	}

}
