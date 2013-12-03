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
		$this -> getRecommendedContent($this -> articleManager, $this -> viewBag['recommended']);
		$this -> getRecommendedContent($this -> columnManager, $this -> viewBag['recommended']);
		$this -> getRecommendedContent($this -> reviewManager, $this -> viewBag['recommended']);
		
		$this -> viewBag['newest'] = array();
		$this -> getNewestContent($this -> articleManager, $this -> viewBag['newest']);
		$this -> getNewestContent($this -> columnManager, $this -> viewBag['newest']);
		$this -> getNewestContent($this -> reviewManager, $this -> viewBag['newest']);

		$this -> viewBag['popular'] = array();
		$this -> getPopularContent($this -> articleManager, $this -> viewBag['popular']);
		$this -> getPopularContent($this -> columnManager, $this -> viewBag['popular']);
		$this -> getPopularContent($this -> reviewManager, $this -> viewBag['popular']);
		
		$this -> renderView();
	}
	
	/**
	 * 
	 */
	private function getRecommendedContent($manager, &$currentData){
		$currentData = array_merge($currentData, $manager-> getRecommended());
	}
	private function getPopularContent($manager, &$currentData){
		$currentData = array_merge($currentData, $manager-> getPopular());
	}
	private function getNewestContent($manager, &$currentData){
		$currentData = array_merge($currentData, $manager-> getNewest());
	}
}
