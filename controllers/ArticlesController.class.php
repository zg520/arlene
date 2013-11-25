<?php
class ArticlesController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new ArticleManager();
	}

	public function getById() {
		if(CurrentUser::getUser() -> isAuthenticated()){
			$this->viewBag['article'] = $this -> modelManager -> getForUserById($this -> uriParams[2], CurrentUser::getUser() -> userId);
		}else{
			$this->viewBag['article'] = $this -> modelManager -> getById($this -> uriParams[2]);
		}
		$this->addNotification(new Notification("info", "Well that's cool!"));
		
		$this -> renderView($this->viewBag);
	}

	public function index() {
		$this->viewBag['all'] = $this -> modelManager -> getById();
		$this -> renderView($this->viewBag);
	}

}
