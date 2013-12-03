<?php
class ReadController extends Controller {
	private $articleManager;
	private $columnManager;
	private $reviewManager;
	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
		$this -> columnManager = new ColumnManager();
		$this -> reviewManager = new ReviewManager();
		$this -> authorizationMapping = array('articlesbydate' => 'reader', 'columnsbydate' => 'reader', 'reviewsbydate' => 'reader', 'content'=>'reader','like' => 'subscriber', 'dislike' => 'subscriber', 'comment' => 'subscriber');
	}
	public function articlesByDate(){
		$this -> viewBag['all'] = $this -> articleManager -> getNewest(1000, 0);
		$this -> viewBag['sort'] = "By Date";
		$this -> renderView(false, ROOT . DS . "views" . DS . "read" . DS . "SharedViewAllContent.php");
	}
	public function columnsByDate(){
		$this -> viewBag['all'] = $this -> columnManager -> getNewest(1000, 0);
		$this -> viewBag['sort'] = "By Date";
		$this -> renderView(false, ROOT . DS . "views" . DS . "read" . DS . "SharedViewAllContent.php");
	}
	public function reviewsByDate(){
		$this -> viewBag['all'] = $this -> reviewManager -> getNewest(1000, 0);
		$this -> viewBag['sort'] = "By Date";
		$this -> renderView(false, ROOT . DS . "views" . DS . "read" . DS . "SharedViewAllContent.php");
	}
	public function content() {
		$this -> viewBag['content'] = $this -> articleManager -> getArticleById($this -> uriParams[2]);
		if (!empty($this -> viewBag['content'])) {
			$this -> renderView();
		return;
		}
		
		$this -> viewBag['content'] = $this -> columnManager -> getColumnById($this -> uriParams[2]);
		if (!empty($this -> viewBag['content'])) {
			$this -> renderView();
			return;
		}
		
		$this -> viewBag['content'] = $this -> reviewManager -> getReviewById($this -> uriParams[2]);
		if (!empty($this -> viewBag['content'])) {
			$this -> renderView();
				return;
		}
	}

	public function like() {
		if ($this -> articleManager -> vote($this -> uriParams[2], CurrentUser::getUser() -> userId, "positive")) {
			$this -> addNotification('info', "Up one vote.");
		} else {
			$this -> addNotification('warn', "You can't vote twice.");
		}
		$this -> renderView(true);
	}

	public function dislike() {
		if ($this -> articleManager -> vote($this -> uriParams[2], CurrentUser::getUser() -> userId, "negative")) {
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
		if(!$this -> articleManager -> addUserCommentToId($_POST['article_id'], CurrentUser::getUser() -> userId, $_POST['comment'])){
			$this -> addNotification('warn', "Something went wrong we couldn't add your comment.");
		}
		$this -> viewBag['redirectUri'] = $_SERVER['HTTP_REFERER'];
		$this -> renderView(true);
	}
}
