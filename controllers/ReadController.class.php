<?php
class ReadController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new ArticleManager();
	}

	public function getById() {
		$this -> viewBag['article'] = $this -> modelManager -> getById($this -> uriParams[2]);
		if (!isset($this -> viewBag['article'])) {
			$this -> addNotification(new Notification("error", "Something went wrong. We cannot display you article right now."));
		}
		$this ->addNotification(new Notification("info", "Some text"));
		$this -> renderView($this -> viewBag);
	}

	public function like() {
		if ($this -> modelManager -> vote($this -> uriParams[2], CurrentUser::getUser()-> userId, "positive")) {
			$this -> addNotification(new Notification('info', "Up one vote."));
		} else {
			$this -> addNotification(new Notification('warn', "You can't vote twice."));
		}
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($this -> viewBag);
	}

	public function dislike() {
		if ($this -> modelManager -> vote($this -> uriParams[2], CurrentUser::getUser()-> userId, "negative")) {
			$this -> addNotification(new Notification('info', "Down one vote."));
		} else {
			$this -> addNotification(new Notification('warn', "You can't vote twice."));
		}
		$this ->viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($this -> viewBag);
	}

	public function index() {
		$this -> viewBag['all'] = $this -> modelManager -> getById();
		$this -> renderView($this -> viewBag);
	}

}
