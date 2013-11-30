<?php
class EditController extends Controller {
	private $articleManager;
	private $columnManager;
	private $reviewManager;

	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
		$this -> columnManager = new ColumnManager();

		$this -> authorizationMapping = array(
				'comment' => 'editor',
				'columns' =>'editor', 
				'preview' => 'writer',
				'articles' => 'editor',
				'article' => 'writer',
				'reviews' =>'editor',
				'index' => 'editor');
	}

	
	public function articles() {
		$this -> viewBag['awaitingChanges'] = $this -> articleManager -> getAll('awaiting_changes');
		$this -> viewBag['underReview'] = $this -> articleManager -> getAll('under_review');
		$this -> viewBag['submitted'] = $this -> articleManager -> getAll('submitted');
		$this -> renderView();
	}
	
	public function article() {
		if(isset($_POST['status']) || isset($_POST['recommended'])){
			if(CurrentUser:: hasEditorAccess()){
				if(isset($_POST['status'])){
					if($this -> articleManager -> changeStatus($_POST['id'], CurrentUser::getUser()->userId, $_POST['status'])){
						$this -> addNotification('info', 'Successfully updated status of the article!');
					}else{
						$this -> addNotification('error', "Couldn't update the article status. Try again!");
					}
				}
				if(isset($_POST['recommended'])){
					if($this -> articleManager -> changeRecommendedStatus($_POST['id'], $_POST['recommended'] == "true")){
						$this -> addNotification('info', 'Successfully recommended the article!');
					}else{
						$this -> addNotification('error', "Couldn't recommend the article. Try again!");
					}
				}
			}else{
				$this -> addNotification('error', "You cant change article status, or recommend it!");
			}
		}
		if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['contents']) && isset($_POST['imgUrl'])){
			if($this -> articleManager -> updateArticle($_POST['id'], $_POST['title'], $_POST['contents'], $_POST['imgUrl'])){
				$this -> addNotification('info', 'Successfully updated the article!');
			}
			else{
				$this -> addNotification('error', "We couldn't update the article. Try again.");
			}
			$this -> viewBag['article'] = $this -> articleManager -> getById($_POST['articleId'], null, true);
			$this -> renderView(true);
			return;
		}
		if (isset($this -> uriParams[2])) {
			$this -> viewBag['article'] = $this -> articleManager -> getById($this -> uriParams[2], null, true);
			$this -> renderView();
		} else {
			$this -> viewBag['article'] = $this -> articleManager -> getById($id);
			$this -> renderView();
		}
	}

	public function reviews() {
		$this -> viewBag['awaitingChanges'] = $this -> reviewManager -> getAll('awaiting_changes');
		$this -> viewBag['underReview'] = $this -> reviewManager -> getAll('under_review');
		$this -> viewBag['submitted'] = $this -> reviewManager -> getAll('submitted');
		$this -> renderView();
	}

	public function columns() {
		$this -> viewBag['awaitingChanges'] = $this -> columnManager -> getAll('awaiting_changes');
		$this -> viewBag['underReview'] = $this -> columnManager -> getAll('under_review');
		$this -> viewBag['submitted'] = $this -> columnManager -> getAll('submitted');
		$this -> renderView();
	}
	public function comment(){
		if (!array_key_exists('comment', $_POST) && !array_key_exists('article_id', $_POST)) {
			$this -> addNotification('warn', 'Upsi.. Daisy.. Something went wrong.');
			$this -> renderView($this -> viewBag, true);
			return;
		}
		if(!$this -> articleManager -> addEditorCommentToId($_POST['article_id'], CurrentUser::getUser() -> userId, $_POST['comment'])){
			$this -> addNotification('warn', "Something went wrong we couldn't add your comment.");
		}
		$this -> renderView(true);
	}
}
