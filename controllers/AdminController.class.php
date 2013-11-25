<?php
class AdminController extends Controller {
	private $articleManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
		
		$this->authorizationMapping['addArticle'] = 'writer';
		$this->authorizationMapping['index'] = 'subscriber';
	}

	public function addArticle() {
		$this->articleManager -> addNewArticle($_POST['title'], $_POST['contents'], $_POST['imgUrl'], currentUser()->userId);
		$this->addNotification(new Notification("info", "Your article has been added successfully :)"));
		$this->viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($this -> viewBag);
	}

	public function index() {
		$currentUser = currentUser();
		$this -> viewBag['awaitingChanges'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "awaiting_changes");
		$this -> viewBag['underReview'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "under_review");
		$this -> viewBag['submitted'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "submitted");
		$this -> viewBag['published'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "published");
		$this -> renderView($this -> viewBag);
	}
}
