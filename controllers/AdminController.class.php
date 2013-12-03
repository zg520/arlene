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
		$this -> reviewManager = new ReviewManager();
		$this -> memberManager = new MemberManager();
		$this -> viewBag['review-topics'] =  json_encode($this -> reviewManager -> getTopics());
		$this -> viewBag['column-topics'] =  json_encode($this -> columnManager -> getTopics());
		
		$this -> authorizationMapping['viewmembers'] = 'publisher';
		$this -> authorizationMapping['editmembers'] = 'publisher';
		$this -> authorizationMapping['changeusertype'] = 'publisher';
		$this -> authorizationMapping['addreview'] = 'writer';
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
		
		if($this -> articleManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],  CurrentUser::getUser() -> userId)){
			$this -> addNotification("info", "Your article has been added successfully :)");
		}else{
			$this -> addNotification("error", "Something went wrong. We couldn't add your article.");
		}
		
		$this -> renderView(true);
	}

	public function addColumn() {
		if($this -> columnManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],$_POST['topic'], CurrentUser::getUser()-> userId)){
			$this -> addNotification("info", "Your column article has been added successfully :)");
				}else{
			$this -> addNotification("error", "Something went wrong. We couldn't add your column.");
		}
				
		$this -> renderView(true);
	}
	
	public function addReview() {
		if($this -> reviewManager -> addNew($_POST['title'], $_POST['contents'], $_POST['imgUrl'],$_POST['topic'],$_POST['rating'], CurrentUser::getUser()-> userId)){
			$this -> addNotification("info", "Your column article has been added successfully :)");
		}else{
			$this -> addNotification("error", "Something went wrong. We couldn't add your review.");
		}
		
		$this -> renderView(true);
	}

	public function index() {
		$currentUser = CurrentUser::getUser();
		$this -> viewBag['awaitingChanges'] = array();
		$this -> viewBag['underReview'] = array();
		$this -> viewBag['submitted'] = array();
		$this -> viewBag['published'] = array();
		$this -> viewBag['rejected'] = array();
		
		$this -> viewBag['awaitingChanges'] = array_merge($this -> viewBag['awaitingChanges'], $this -> articleManager -> getWriterContent($currentUser -> userId, "awaiting_changes"));
		$this -> viewBag['awaitingChanges'] = array_merge($this -> viewBag['awaitingChanges'], $this -> columnManager -> getWriterContent($currentUser -> userId, "awaiting_changes"));
		$this -> viewBag['awaitingChanges'] = array_merge($this -> viewBag['awaitingChanges'], $this -> reviewManager -> getWriterContent($currentUser -> userId, "awaiting_changes"));
		
		$this -> viewBag['underReview'] = array_merge($this -> viewBag['underReview'], $this -> articleManager -> getWriterContent($currentUser -> userId, "under_review"));
		$this -> viewBag['underReview'] = array_merge($this -> viewBag['underReview'], $this -> columnManager -> getWriterContent($currentUser -> userId, "under_review"));
		$this -> viewBag['underReview'] = array_merge($this -> viewBag['underReview'], $this -> reviewManager -> getWriterContent($currentUser -> userId, "under_review"));

		$this -> viewBag['submitted'] = array_merge($this -> viewBag['submitted'], $this -> articleManager -> getWriterContent($currentUser -> userId, "submitted"));
		$this -> viewBag['submitted'] = array_merge($this -> viewBag['submitted'], $this -> columnManager -> getWriterContent($currentUser -> userId, "submitted"));
		$this -> viewBag['submitted'] = array_merge($this -> viewBag['submitted'], $this -> reviewManager -> getWriterContent($currentUser -> userId, "submitted"));
		
		$this -> viewBag['published'] = array_merge($this -> viewBag['published'], $this -> articleManager -> getWriterContent($currentUser -> userId, "published"));
		$this -> viewBag['published'] = array_merge($this -> viewBag['published'], $this -> columnManager -> getWriterContent($currentUser -> userId, "published"));
		$this -> viewBag['published'] = array_merge($this -> viewBag['published'], $this -> reviewManager -> getWriterContent($currentUser -> userId, "published"));
		
		$this -> viewBag['rejected'] = array_merge($this -> viewBag['rejected'], $this -> articleManager -> getWriterContent($currentUser -> userId, "rejected"));
		$this -> viewBag['rejected'] = array_merge($this -> viewBag['rejected'], $this -> columnManager -> getWriterContent($currentUser -> userId, "rejected"));
		$this -> viewBag['rejected'] = array_merge($this -> viewBag['rejected'], $this -> reviewManager -> getWriterContent($currentUser -> userId, "rejected"));
		
		$this -> renderView();
	}

}
