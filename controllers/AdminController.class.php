<?php
class AdminController extends Controller {
	private $articleManager;
	private $columnManager;
	private $reviewManager;
	private $memberMapper;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
		$this -> columnManager = new ColumnManager();
		
		$this -> authorizationMapping['changeusertype'] = 'publisher';
		$this -> authorizationMapping['editarticle'] = 'editor';
		$this -> authorizationMapping['addarticle'] = 'writer';
		$this -> authorizationMapping['addcolumn'] = 'writer';
		$this -> authorizationMapping['index'] = 'subscriber';
	}

	public function editArticle(){
		if(isset($this->uriParams[2])){
			
		}else{
			$currentUser = CurrentUser::getUser();
			$this -> viewBag['awaitingChanges'] = $this -> articleManager -> getAll('awaiting_changes');
			$this -> viewBag['underReview'] = $this -> articleManager -> getAll('under_review');
			$this -> viewBag['submitted'] = $this -> articleManager -> getAll('submitted');
			$this -> renderView($this -> viewBag);
		}
	}
	public function addArticle() {
		$this -> articleManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],  CurrentUser::getUser() -> userId);
		$this -> addNotification(new Notification("info", "Your article has been added successfully :)"));
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($this -> viewBag, true);
	}

	public function addColumn() {
		$this -> columnManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],$_POST['topic'], CurrentUser::getUser()-> userId);
		$this -> addNotification(new Notification("info", "Your column article has been added successfully :)"));
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($this -> viewBag, true);
	}

	public function index() {
		$currentUser = CurrentUser::getUser();
		$this -> viewBag['awaitingChanges'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "awaiting_changes");
		$this -> viewBag['underReview'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "under_review");
		$this -> viewBag['submitted'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "submitted");
		$this -> viewBag['published'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "published");
		$this -> renderView($this -> viewBag);
	}

}
