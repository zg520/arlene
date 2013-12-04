<?php
/**
 * A class to hold Article content information.
 *
 * @access default
 * @package Common\Model\Contract
 */
class Article {

	/**
	 * The id of the article.
	 *
	 * @access public
	 */
	public $id;

	/**
	 * The title of the article.
	 *
	 * @access public
	 */
	public $title;

	/**
	 * The body text of the article.
	 *
	 * @access public
	 */
	public $body;

	/**
	 * The cover url of the article.
	 *
	 * @access public
	 */
	public $coverUrl;

	/**
	 * The number of likes.
	 *
	 * @access public
	 */
	public $likes;

	/**
	 * The number of dislikes.
	 *
	 * @access public
	 */
	public $dislikes;

	/**
	 * The publish date.
	 * Set as the date when the editor has published the article.
	 *
	 * @access public
	 */
	public $publishDate;

	/**
	 * The creation date of likes. Set as the date when the writer
	 * has submitted the article.
	 *
	 * @access public
	 */
	public $createdDate;

	/**
	 * The public comments of the article.
	 * Displayed to everyone.
	 *
	 * @access public
	 */
	public $publicComments;

	/**
	 * The editor comments of the article.
	 * Not visible publicly.
	 *
	 * @access public
	 */
	public $editorComments;

	/**
	 * The id of the editor published the article.
	 *
	 * @access public
	 */
	public $editor;

	/**
	 * The members who contributed to writing the article.
	 * @See Member class.
	 *
	 * @access public
	 */
	public $writers;

	/**
	 * The current status of the article.
	 *
	 * @access public
	 */
	public $status;

	/**
	 * Generates a short up to 150 characters summary of the article.
	 *
	 * @access public
	 * @return string The summary of the article.
	 */
	public function getSummary() {
		if ($this -> body != null) {
			return substr($this -> body, 0, 150) . '...';
		}
		return 'No more to read.';
	}

}
