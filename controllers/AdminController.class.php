<?php
class AdminController extends Controller {
	private $articleManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
	}

	public function addArticle() {
		$this -> renderView($this -> viewBag);
	}

	public function index() {
		$currentUser = currentUser();
		$this -> viewBag['pendingArticles'] = $this -> articleManager -> getPendingWriterArticles($currentUser -> userId);

		$this -> renderView($this -> viewBag);
	}

}
