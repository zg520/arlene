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
		if(empty($review)){
			return false;
		}
		$resultTopic = $this -> query("Select `topic`, `rating` FROM `reviewarticles` WHERE `article_id` = :id", array('id' => $id));
		$review -> topic = $resultTopic['topic'];
		$review -> rating = $resultTopic['rating'];
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
	
	
	
		public function getForUserById($id, $user) {
		$countRows = "SELECT COUNT(*) FROM `articlewriters` WHERE `user_id` = :user AND `article_id` = :id";
		$rowCount = $this -> query($countRows, array("id" => $id, "user" => $user));
		if ($rowCount[0][0] > 0) {
			$sql = "SELECT * FROM `full_articles` WHERE `id` = :id";
			$result = $this -> query($sql, array('id' => $id));
			$article = $this -> toSingleObject($this -> objMapper -> toArticles($result));
			$article -> writers = $this -> getArticleWriters($article -> id);
			$article -> likes = $this -> getArticleLikesOrDislikes($article -> id, "positive");
			$article -> dislikes = $this -> getArticleLikesOrDislikes($article -> id, "negative");
			return $article;
		}
		return null;
	}
}
