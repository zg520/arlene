<?php
/**
 * A class to Manage the interaction between
 * the data source and the application's @see Review objects.
 * Extends @See CoontentManager.
 *
 * @access default
 * @package Common\Model\Managers
 */
class ReviewManager extends ContentManager {

	/**
	 * Fetches reviews by id.
	 *
	 * @access public
	 *
	 * @param string $id The review Id.
	 * @param string $status The status of the article. Optional parameter with default value of "published".
	 * @param bool $editorCommentsIncluded Whether to include editor comments when constructing the Review.
	 *
	 * @return Review The review from the database or empty.
	 */
	public function getReviewById($id, $status = "published", $editorCommentsIncluded = false) {
		$review = $this -> getById($id, "Review", $status, $editorCommentsIncluded);

		if (empty($review)) {
			return false;
		}

		$resultTopic = $this -> query("Select `topic`, `rating` FROM `reviewarticles` WHERE `article_id` = :id", array("id" => $id));
		$review -> topic = $resultTopic[0]["topic"];
		$review -> rating = $resultTopic[0]["rating"];
		return $review;
	}

	/**
	 * Fetches all reviews from a list with the newest content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Articles or empty .
	 */
	public function getNewest($top = 5, $skip = 0) {
		$newest = $this -> getNewestIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getReviewById($row[0]));
		}
		return array_filter($results);
	}

	/**
	 * Fetches all reviews from a list with the most popular content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Articles or empty .
	 */
	public function getPopular($top = 5, $skip = 0) {
		$newest = $this -> getMostPopularIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getReviewById($row[0]));
		}
		return array_filter($results);
	}

	/**
	 * Fetches all reviews from a list with recommended content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Articles or empty .
	 */
	public function getRecommended($top = 5, $skip = 0) {
		$newest = $this -> getRecommendedIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getReviewById($row[0]));
		}
		return array_filter($results);
	}

	/**
	 * Gets the all the reviews by using their status.
	 *
	 * @access public
	 *
	 * @param string $status The article status.
	 * @return array The articles with specific status associated with an user.
	 */
	public function getAllReviews($status) {
		$all = $this -> getAllIds($status, "review");
		$results = array();
		foreach ($all as $row) {
			array_push($results, $this -> getReviewById($row[0], $status));
		}
		return array_filter($results);
	}

	/**
	 * Gets the content associated with a writer by using their status.
	 *
	 * @access public
	 *
	 * @param string $userId The user id.
	 * @param string $status The article status.
	 *
	 * @return array The articles with specific status associated with an user.
	 */
	public function getWriterContent($userId, $status) {
		$content = $this -> getWriterArticlesIds($userId, $status);

		$results = array();
		foreach ($content as $row) {
			array_push($results, $this -> getReviewById($row[0], $status));
		}
		return array_filter($results);
	}

	/**
	 * Gets all valid topics for reviews.
	 *
	 * @access public
	 *
	 * @return array The topics as strings.
	 */
	public function getTopics() {
		$topicsSql = "SELECT * FROM `reviewtopics`";
		$topics = $this -> query($topicsSql);
		$topicsArray = array();
		foreach ($topics as $topic) {
			array_push($topicsArray, $topic['topic']);
		}
		return $topicsArray;
	}

	/**
	 * Updates a review.
	 *
	 * @access public
	 *
	 * @param string $id The review id.
	 * @param string $title The review title.
	 * @param string $content The review text body content.
	 * @param string $topic The review topic.
	 * @param string $rating The review rating.
	 * @param string $imgUrl The article image url.
	 *
	 * @return bool True if the operation was successful, false otherwise.
	 */
	public function update($id, $title, $content, $imgUrl, $topic, $rating) {
		$updateSql = "UPDATE `articles` SET `title` =:title ,`text_body` = :text_body, `cover_uri` =:cover_uri WHERE `id` = :id";
		$id = $this -> upsert($updateSql, array("id" => $id, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		if ($id == null) {
			return false;
		}
		$updateColSql = "UPDATE `reviewarticles` SET `topic` =:topic, `rating` = :rating WHERE `article_id` = :id";
		$colId = $this -> upsert($updateColSql, array("id" => $id, "topic" => $topic, "rating" => $rating));
		if ($id == null) {
			return false;
		}
		return true;
	}

	/**
	 * Adds a review.
	 *
	 * @access public
	 *
	 * @param string $title The review title.
	 * @param string $content The review text body content.
	 * @param string $imgUrl The review image url.
	 * @param string $topic The review topic.
	 * @param string $rating The review rating.
	 * @param string $userId The writer id.
	 *
	 * @return bool True if the operation was successful, false otherwise.
	 */
	public function addNew($title, $content, $imgUrl, $topic, $rating, $userId) {
		try {
			$id = Utility::generateArticleId($title);
			$insertArticleSql = "INSERT into `articles` (`id`,`title`, `text_body`, `cover_uri`, `type`) VALUES(:id, :title, :text_body, :cover_uri, 'review')";
			$articleId = $this -> upsert($insertArticleSql, array("id" => $id, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
			$insertColumnSql = "INSERT into `reviewarticles` (`article_id`, `topic`, `rating`) VALUES(:article_id, :topic, :rating)";
			$this -> upsert($insertColumnSql, array("article_id" => $id, "topic" => $topic, "rating" => $rating));
			$linkToUserSql = "INSERT into `articlewriters` (`article_id`, `user_id`) VALUES(:article_id, :user_id)";
			$this -> upsert($linkToUserSql, array("article_id" => $id, "user_id" => $userId));
			return true;
		} catch(Exception $e) {
			return false;
		}
	}

}
