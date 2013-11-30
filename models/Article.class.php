<?php
class Article {
	public $id;
	public $title;
	public $body;
	public $coverUrl;
	public $likes;
	public $dislikes;
	public $publishDate;
	public $createdDate;
	public $publicComments;
	public $editorComments;
	public $editor;
	public $writers;
	public $status;
	protected $summary;

	public function getSummary() {
		if ($this->body != null) {
			return substr($this -> body, 0, 150) . '...';
		}
		return 'No more to read.';
	}

}
