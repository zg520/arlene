<?php
class EditController extends Controller {
	private $articleManager;
	private $columnManager;
	private $reviewManager;

	public function __construct($action, $uriParams) {
		parent::__construct($action, $uriParams);
		$this -> articleManager = new ArticleManager();
		$this -> columnManager = new ColumnManager();

		$this -> authorizationMapping['columns'] = 'editor';
		$this -> authorizationMapping['articles'] = 'editor';
		$this -> authorizationMapping['article'] = 'editor';
		$this -> authorizationMapping['reviews'] = 'editor';
		$this -> authorizationMapping['index'] = 'editor';
	}

	public function articles() {
		$this -> viewBag['awaitingChanges'] = $this -> articleManager -> getAll('awaiting_changes');
		$this -> viewBag['underReview'] = $this -> articleManager -> getAll('under_review');
		$this -> viewBag['submitted'] = $this -> articleManager -> getAll('submitted');
		$this -> renderView($this -> viewBag);
	}

	public function article() {
		if (isset($this -> uriParams[2])) {
			$this -> viewBag['article'] = $this -> articleManager -> getById($this -> uriParams[2], null);
			$this -> renderView($this -> viewBag);
		} else {
			$this -> viewBag['article'] = $this -> articleManager -> getById($id);
			$this -> renderView($this -> viewBag);
		}
	}

	public function reviews() {
		$this -> viewBag['awaitingChanges'] = $this -> reviewManager -> getAll('awaiting_changes');
		$this -> viewBag['underReview'] = $this -> reviewManager -> getAll('under_review');
		$this -> viewBag['submitted'] = $this -> reviewManager -> getAll('submitted');
		$this -> renderView($this -> viewBag);
	}

	public function columns() {
		$this -> viewBag['awaitingChanges'] = $this -> columnManager -> getAll('awaiting_changes');
		$this -> viewBag['underReview'] = $this -> columnManager -> getAll('under_review');
		$this -> viewBag['submitted'] = $this -> columnManager -> getAll('submitted');
		$this -> renderView($this -> viewBag);
	}

}
