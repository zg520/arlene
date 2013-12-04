<?php
/**
 * A class to hold comment information.
 *
 * @access default
 * @package Common\Model\Contract
 */
class Comment {
	
	/**
	 * The id of the user posted the comment.
	 *
	 * @access public
	 */
	public $userId;
	
	/**
	 * The date when the comment was published.
	 *
	 * @access public
	 */
	public $datePublished;
	
	/**
	 * The comment text.
	 *
	 * @access public
	 */
	public $comment;
}
