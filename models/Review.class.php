<?php
/**
 * A class to hold Review information. Extends @see Article.
 *
 * @access default
 * @package Contract
 */
class Review extends Article {

	/**
	 * The topic of the review.
	 *
	 * @access public
	 */
	public $topic;

	/**
	 * The rating of the review.
	 *
	 * @access public
	 */
	public $rating;
}
