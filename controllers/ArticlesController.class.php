<?php
class ArticlesController extends Controller {
	public function __construct($action, $uriParams) {
		parent::__construct(new ArticleManager(), $action, $uriParams);
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
