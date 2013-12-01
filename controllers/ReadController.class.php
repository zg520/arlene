<?php
class ReadController extends Controller {
	private $modelManager;
	private $columnManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> modelManager = new ArticleManager();
		$this -> columnManager = new ColumnManager();
		$this -> authorizationMapping = array('index' => 'reader', 'article' => 'reader', 'column'=>'reader','like' => 'subscriber', 'dislike' => 'subscriber', 'comment' => 'subscriber');
	}

	public function article() {
		$this -> viewBag['article'] = $this -> modelManager -> getById($this -> uriParams[2]);
		if (!isset($this -> viewBag['article'])) {
			$this -> addNotification("error", "Something went wrong. We cannot display you article right now.");
		}
		
		$this -> renderView();
	}
	
	public function column() {
		$this -> viewBag['column'] = $this -> columnManager -> getColumnById($this -> uriParams[2]);
		if (!isset($this -> viewBag['column'])) {
			$this -> addNotification("error", "Something went wrong. We cannot display you article right now.");
		}
		
		$this -> renderView();
	}

	public function like() {
		if ($this -> modelManager -> vote($this -> uriParams[2], CurrentUser::getUser() -> userId, "positive")) {
			$this -> addNotification('info', "Up one vote.");
		} else {
			$this -> addNotification('warn', "You can't vote twice.");
		}
		$this -> renderView(true);
	}

	public function dislike() {
		if ($this -> modelManager -> vote($this -> uriParams[2], CurrentUser::getUser() -> userId, "negative")) {
			$this -> addNotification('info', "Down one vote.");
		} else {
			$this -> addNotification('warn', "You can't vote twice.");
		}
		$this -> renderView(true);
	}

	public function comment() {
		if (!array_key_exists('comment', $_POST) && !array_key_exists('article_id', $_POST)) {
			$this -> addNotification('warn', 'Upsi.. Daisy.. Something went wrong.');
			$this -> renderView(true);
			return;
		}
		if(!$this -> modelManager -> addUserCommentToId($_POST['article_id'], CurrentUser::getUser() -> userId, $_POST['comment'])){
			$this -> addNotification('warn', "Something went wrong we couldn't add your comment.");
		}
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView(true);
	}

	public function index() {
		$this -> viewBag['all'] = $this -> modelManager -> getById();
		$this -> renderView();
	}

}
