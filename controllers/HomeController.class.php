<?php
class HomeController extends Controller {
	public function __construct($action, $uriParams) {
		parent::__construct(new ArticleManager(), $action, $uriParams);
	}

	public function index() {
		$model =  array();
		$model['recommended'] = $this -> modelManager -> getRecommended();
		$model['newest'] = $this -> modelManager -> getAll();
		$model['popular'] = $this -> modelManager -> getRecommended();
		$this -> renderView($model);
	}
}
