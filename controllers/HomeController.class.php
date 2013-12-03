<?php
/**
 * A class providing functionality for the HomeController actions
 *
 * @package Common\Controllers
 */
class HomeController extends Controller {
	/**
	 * The article manager.
	 * 
	 * @access private 
	 * 
	 * @var ArticleManager
	 */
	private $articleManager;
	
	/**
	 * The column manager.
	 * 
	 * @access private 
	 * 
	 * @var ColumnManager
	 */
	private $columnManager;
	
	/**
	 * Initialises default instance of @see HomeController class.
	 */
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
		$this-> columnManager = new ColumnManager();
		$this -> reviewManager = new ReviewManager();
	}
	
	/**
	 * Implements the index/default action of the home controller.
	 * 
	 * @access public 
	 * 
	 * @return void
	 */
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
	 * Gets all all the recommended content including Articles, Reviews and Columns.
	 * 
	 * @access private 
	 * 
	 * @param ContentManager $manager The manager from which to get the data.
	 * @param array &$data Where to add the data to.
	 * 
	 * @return void
	 */
	private function getRecommendedContent($manager, &$currentData){
		$currentData = array_merge($currentData, $manager-> getRecommended());
	}
	
	/**
	 * Gets all all the popular content including Articles, Reviews and Columns.
	 * 
	 * @access private 
	 * 
	 * @param ContentManager $manager The manager from which to get the data.
	 * @param array &$data Where to add the data to.
	 * 
	 * @return void
	 */
	private function getPopularContent($manager, &$currentData){
		$currentData = array_merge($currentData, $manager-> getPopular());
	}
	
	/**
	 * Gets all all the newest content including Articles, Reviews and Columns.
	 * 
	 * @access private 
	 * 
	 * @param ContentManager $manager The manager from which to get the data.
	 * @param array &$data Where to add the data to.
	 * 
	 * @return void
	 */
	private function getNewestContent($manager, &$currentData){
		$currentData = array_merge($currentData, $manager-> getNewest());
	}
}
