<?php
class ReadController extends Controller {
	private $modelManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new ArticleManager();

		$this -> authorizationMapping = array('index' => 'reader', 'article' => 'reader', 'like' => 'subscriber', 'dislike' => 'subscriber', 'comment' => 'subscriber');
	}

	public function article() {
		$this -> viewBag['article'] = $this -> modelManager -> getById($this -> uriParams[2]);
		if (!isset($this -> viewBag['article'])) {
			$this -> addNotification(new Notification("error", "Something went wrong. We cannot display you article right now."));
		}
		
		$this -> renderView($this -> viewBag);
	}

	public function like() {
		if ($this -> modelManager -> vote($this -> uriParams[2], CurrentUser::getUser() -> userId, "positive")) {
			$this -> addNotification(new Notification('info', "Up one vote."));
		} else {
			$this -> addNotification(new Notification('warn', "You can't vote twice."));
		}
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($this -> viewBag, true);
	}

	public function dislike() {
		if ($this -> modelManager -> vote($this -> uriParams[2], CurrentUser::getUser() -> userId, "negative")) {
			$this -> addNotification(new Notification('info', "Down one vote."));
		} else {
			$this -> addNotification(new Notification('warn', "You can't vote twice."));
		}
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($this -> viewBag, true);
	}

	public function comment() {
		if (!array_key_exists('comment', $_POST) && !array_key_exists('article_id', $_POST)) {
			$this -> addNotification(new Notification('warn', 'Upsi.. Daisy.. Something went wrong.'));
			$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
			$this -> renderView($this -> viewBag, true);
			return;
		}
		if(!$this -> modelManager -> addUserCommentToId($_POST['article_id'], CurrentUser::getUser() -> userId, $_POST['comment'])){
			$this -> addNotification(new Notification('warn', "Something went wrong we couldn't add your comment."));
		}
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView($this -> viewBag, true);
	}

	public function index() {
		$this -> viewBag['all'] = $this -> modelManager -> getById();
		$this -> renderView($this -> viewBag);
	}

}
