<?php
/**
 * A class providing functionality for the AdminController actions
 *
 * @package Common\Controllers
 */
class AdminController extends Controller {
	
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
	 * The review manager.
	 * 
	 * @access private 
	 * 
	 * @var ReviewManager
	 */
	private $reviewManager;
	
	/**
	 * The member manager.
	 * 
	 * @access private 
	 * 
	 * @var MemberManager
	 */
	private $memberMapper;
	
	/**
	 * Initialises default instance of @see AdminController class.
	 * 
	 * @access public
	 */
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
		$this -> columnManager = new ColumnManager();
		$this -> reviewManager = new ReviewManager();
		$this -> memberManager = new MemberManager();
		$this -> viewBag['review-topics'] =  json_encode($this -> reviewManager -> getTopics());
		$this -> viewBag['column-topics'] =  json_encode($this -> columnManager -> getTopics());
		
		$this -> authorizationMapping['viewmembers'] = 'publisher';
		$this -> authorizationMapping['editmembers'] = 'publisher';
		$this -> authorizationMapping['changeusertype'] = 'publisher';
		$this -> authorizationMapping['addreview'] = 'writer';
		$this -> authorizationMapping['addarticle'] = 'writer';
		$this -> authorizationMapping['addcolumn'] = 'writer';
		$this -> authorizationMapping['index'] = 'subscriber';
	}
	
	/**
	 * Implements the viewMembers action of the @see HomeController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function viewMembers(){
		$this->viewBag['members'] = $this -> memberManager -> getAllMembers();
		$this -> renderView();
	}
	
	 /**
	 * Implements the editMembers action of the @see HomeController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function editMembers(){
		if(isset($_POST['ids']) && count($_POST['ids']) == count($_POST['roles'])){
			for($i = 0; $i < count($_POST['ids']); $i++){
				if($_POST['ids'][$i] != CurrentUser::getUser()->userId){
					if($this -> memberManager -> updateMemberRole($_POST['ids'][$i], $_POST['roles'][$i])){
						$this -> addNotification('error', "Couldn't update '" . $member['id'] . "'.");
					}else{
						$this -> addNotification('info', "Updated '" . $_POST['ids'][$i] . "'!");
					}
				}else{
					$this -> addNotification('error', "You can't change your own permissions!");
				}
			}
		}else{
			$this -> addNotification('error', "Bad data sent to the server.");
		}
		$this -> renderView(true);
	}
	
	 /**
	 * Implements the addArticle action of the @see HomeController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function addArticle() {
		
		if($this -> articleManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],  CurrentUser::getUser() -> userId)){
			$this -> addNotification("info", "Your article has been added successfully :)");
		}else{
			$this -> addNotification("error", "Something went wrong. We couldn't add your article.");
		}
		
		$this -> renderView(true);
	}
	 /**
	 * Implements the addColumn action of the @see HomeController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function addColumn() {
		if($this -> columnManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],$_POST['topic'], CurrentUser::getUser()-> userId)){
			$this -> addNotification("info", "Your column article has been added successfully :)");
				}else{
			$this -> addNotification("error", "Something went wrong. We couldn't add your column.");
		}
				
		$this -> renderView(true);
	}
	
	 /**
	 * Implements the addReview action of the @see HomeController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function addReview() {
		if($this -> reviewManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],$_POST['topic'],$_POST['rating'], CurrentUser::getUser()-> userId)){
			$this -> addNotification("info", "Your column article has been added successfully :)");
		}else{
			$this -> addNotification("error", "Something went wrong. We couldn't add your review.");
		}
		
		$this -> renderView(true);
	}
	 /**
	 * Implements the index/default action of the @see HomeController.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function index() {
		$this -> viewBag['awaitingChanges'] = array();
		$this -> viewBag['underReview'] = array();
		$this -> viewBag['submitted'] = array();
		$this -> viewBag['published'] = array();
		$this -> viewBag['rejected'] = array();
		
		$this -> populateWriterArticles($this -> viewBag['awaitingChanges'], "awaiting_changes");
		$this -> populateWriterArticles($this -> viewBag['underReview'], "under_review");
		$this -> populateWriterArticles($this -> viewBag['submitted'], "submitted");
		$this -> populateWriterArticles($this -> viewBag['published'], "published");
		$this -> populateWriterArticles($this -> viewBag['rejected'], "rejected");
	
		$this -> renderView();
	}
	
	/**
	 * Gets all the writers' content by state.
	 * 
	 * @access private 
	 * 
	 * @param array &$data Where to add the data to.
	 * @param string $contentState The state of the content to fetch.
	 */
	private function populateWriterArticles(&$currentData, $contentState){
		$currentData = array_merge($currentData, $this-> articleManager -> getWriterContent(CurrentUser::getUser() -> userId, $contentState));
		$currentData = array_merge($currentData, $this->columnManager -> getWriterContent(CurrentUser::getUser() -> userId, $contentState));
		$currentData = array_merge($currentData, $this->reviewManager -> getWriterContent(CurrentUser::getUser() -> userId, $contentState));
	}
}
