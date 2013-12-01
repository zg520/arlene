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
		$this -> memberManager = new MemberManager();
		
		$this -> authorizationMapping['viewmembers'] = 'publisher';
		$this -> authorizationMapping['editmembers'] = 'publisher';
		$this -> authorizationMapping['changeusertype'] = 'publisher';
		$this -> authorizationMapping['addarticle'] = 'writer';
		$this -> authorizationMapping['addcolumn'] = 'writer';
		$this -> authorizationMapping['index'] = 'subscriber';
	}

	public function viewMembers(){
		$this->viewBag['members'] = $this -> memberManager -> getAllMembers();
		$this -> renderView();
	}
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
	public function addArticle() {
		$this -> articleManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],  CurrentUser::getUser() -> userId);
		$this -> addNotification("info", "Your article has been added successfully :)");
		$this -> renderView(true);
	}

	public function addColumn() {
		$this -> columnManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],$_POST['topic'], CurrentUser::getUser()-> userId);
		$this -> addNotification("info", "Your column article has been added successfully :)");
		$this -> renderView(true);
	}

	public function index() {
		$currentUser = CurrentUser::getUser();
		$this -> viewBag['awaitingChanges'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "awaiting_changes");
		$this -> viewBag['underReview'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "under_review");
		$this -> viewBag['submitted'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "submitted");
		$this -> viewBag['published'] = $this -> articleManager -> getWriterArticles($currentUser -> userId, "published");
		$this -> renderView();
	}

}
