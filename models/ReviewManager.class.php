<?php
class ReviewManager extends ContentManager {

	/**
	 * Fetches reviews by id.
	 *
	 * @param string $id The review Id.
	 * @param string $status The status of the article. Optional parameter with default value of "published".
	 * @param bool $editorCommentsIncluded Whether to include editor comments when constructing the Review.
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

	public function getNewest($top = 5, $skip = 0) {
		$newest = $this -> getNewestIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getReviewById($row[0]));
		}
		return array_filter($results);
	}

	public function getPopular($top = 5, $skip = 0) {
		$newest = $this -> getMostPopularIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getReviewById($row[0]));
		}
		return array_filter($results);
	}

	public function getRecommended($top = 5, $skip = 0) {
		$newest = $this -> getRecommendedIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getReviewById($row[0]));
		}
		return array_filter($results);
	}

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
	 * @param string $userId The user id.
	 * @param string $status The article status.
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
	public function getTopics(){
		$topicsSql = "SELECT * FROM `reviewtopics`";
		$topics = $this -> query($topicsSql);
		$topicsArray = array();
		foreach ($topics as $topic) {
			array_push($topicsArray, $topic['topic']);
		}
		return $topicsArray;
	}
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

}
