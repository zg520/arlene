<?php
class ColumnManager extends ContentManager {

	/**
	 * Fetches columns by id.
	 *
	 * @param string $id The column Id.
	 * @param string $status The status of the article. Optional parameter with default value of "published".
	 * @param bool $editorCommentsIncluded Whether to include editor comments when constructing the Article.
	 * @return Column The column from the database or empty.
	 */
	public function getColumnById($id, $status = "published", $editorCommentsIncluded = false) {
		$column = $this -> getById($id, "Column", $status, $editorCommentsIncluded);

		if(empty($column)){
			return false;
		}
		$resultTopic = $this -> query("Select `topic` FROM `columnarticles` WHERE `article_id` = :id", array('id' => $id));
		$column -> topic = $resultTopic[0]['topic'];
		return $column;
	}

	public function getForUserById($id, $user) {
		$countRows = "SELECT COUNT(*) FROM `writers` WHERE `user_id` = :user AND `article_id` = :id";
		$rowCount = $this -> query($countRows, array("id" => $id, "user" => $user));
		if ($rowCount > 0) {
			$sql = "SELECT * FROM `full_columns` WHERE `id` = ?";
			$result = $this -> query($sql, array($id));
			$column = $this -> toSingleObject($result);
			
		}
		return null;
	}

	public function getNewest($top = 5, $skip = 0) {
		$newest = $this -> getNewestIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getColumnById($row[0]));
		}
		return array_filter($results);
	}
	
	public function getPopular($top = 5, $skip = 0) {
		$newest = $this -> getMostPopularIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getColumnById($row[0]));
		}
		return array_filter($results);
	}
	
	public function getRecommended($top = 5, $skip = 0) {
		$newest = $this -> getRecommendedIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getColumnById($row[0]));
		}
		return array_filter($results);
	}
	
	public function getWriterArticles($userId, $status) {
		$result = $this -> query("SELECT * FROM `writers` INNER JOIN `articles` ON `articles`.`id` = `writers`.`article_id` WHERE `user_id` = ? AND `status` = ?", array($userId, $status));

		return $this -> toObjects($result);
	}


	public function addNew($title, $content, $imgUrl, $topic, $userId) {
		$insertArticleSql = "INSERT into `articles` (`title`, `text_body`, `cover_uri`, `type`) VALUES(:title, :text_body, :cover_uri, 'article')";
		$articleId = $this -> upsert($insertArticleSql, array("title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		$insertColumnSql = "INSERT into `columnarticles` (`article_id`, `topic`) VALUES(:article_id, :topic)";
		$this -> upsert($insertColumnSql, array("article_id" => $articleId, "topic" => $topic));
		$linkToUserSql = "INSERT into `writers` (`article_id`, `user_id`) VALUES(:article_id, :user_id)";
		$this -> upsert($linkToUserSql, array("article_id" => $articleId, "user_id" => $userId));
		return null;
	}

}
