<?php
class HomeController extends Controller {
	private $articleManager;
	private $columnManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
		$this-> columnManager = new ColumnManager();
		$this -> reviewManager = new ReviewManager();
	}

	public function index() {
		$this -> viewBag['recommended'] = array();
		$this -> viewBag['recommended'] = array_merge($this -> viewBag['recommended'], $this -> articleManager -> getRecommended());
		$this -> viewBag['recommended'] = array_merge($this -> viewBag['recommended'], $this -> columnManager -> getRecommended());
		$this -> viewBag['recommended']= array_merge($this -> viewBag['recommended'],  $this -> reviewManager -> getRecommended());
		
		$this -> viewBag['newest'] = array();
		$this -> viewBag['newest'] = array_merge($this -> viewBag['newest'], $this -> articleManager -> getNewest());
		$this -> viewBag['newest'] = array_merge($this -> viewBag['newest'], $this -> columnManager -> getNewest());
		$this -> viewBag['newest']= array_merge($this -> viewBag['newest'],  $this -> reviewManager -> getNewest());
		
		$this -> viewBag['popular'] = array();
		$this -> viewBag['popular'] = array_merge($this -> viewBag['popular'], $this -> articleManager -> getPopular());
		$this -> viewBag['popular'] = array_merge($this -> viewBag['popular'], $this -> columnManager -> getPopular());
		$this -> viewBag['popular']= array_merge($this -> viewBag['popular'],  $this -> reviewManager -> getPopular());
		
		$this -> renderView();
	}

}
