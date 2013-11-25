<?php
class ArticleManager extends DataManager {
	/**
	 * Mapping rules for creating article object.
	 *
	 * @var array
	 */
	public $articleMapping = array('id' => 'id', 'article_id' => 'id', 'title' => 'title', 'text_body' => 'body', 'cover_uri' => 'coverUrl', 'likes' => 'likes', 'dislikes' => 'dislikes', 'publish_date' => 'publishDate', 'writers' => 'writers', 'user_id' => 'writers', 'status' => 'status');

	public function getById($id, $status = "published") {

		$sql = "SELECT * FROM `full_articles` WHERE status = :status AND `id` = :id";
		$result = $this -> query($sql, array('id' => $id, 'status' => $status));
		return $this -> toSingleObject($result);
	}
	public function addEditorCommentToId($id, $editorId, $comment){
		$insertArticleSql = "INSERT into `editorials` (`article_id`, `user_id`, `comment`) VALUES(:id, :editorId, :comment)";
		$articleId = $this -> upsert($insertArticleSql, array("articleId" => $id, "editorId" => $editorId, "comment" => $comment));	
	}
	public function changeStatus($id, $editorId, $newStatus){
		$insertArticleSql = "INSERT into `articles` WHERE `id` = :id (`status`) VALUES(:status)";
		$articleId = $this -> upsert($insertArticleSql, array("id" => $id, "status" => $newStatus));	
	}
	
	public function getForUserById($id, $user) {

		$countRows = "SELECT COUNT(*) FROM `writers` WHERE `user_id` = :user AND `article_id` = :id";
		$rowCount = $this -> query($countRows, array("id" => $id, "user" => $user));
		if ($rowCount > 0) {
			$sql = "SELECT * FROM `full_articles` WHERE `id` = ?";
			$result = $this -> query($sql, array($id));
			return $this -> toSingleObject($result);
		}
		return null;
	}

	public function getNewest($top = 5, $skip = 0) {
		$sql = "SELECT * FROM `full_articles` WHERE status = 'published' ORDER BY `publish_date` DESC LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		return $this -> toObjects($result);
	}

	public function getWriterArticles($userId, $status) {
		$result = $this -> query("SELECT * FROM `writers` INNER JOIN `articles` ON `articles`.`id` = `writers`.`article_id` WHERE `user_id` = ? AND `status` = ?", array($userId, $status));

		return $this -> toObjects($result);
	}

	public function getAll($top = 5, $skip = 0) {
		$sql = "SELECT * FROM `full_articles` WHERE status = 'published' LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		return $this -> toObjects($result);
	}

	public function getRecommended($top = 5, $skip = 0) {
		$sql = "SELECT * FROM `full_articles` WHERE status = 'published' AND recommended = 'true' LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		return $this -> toObjects($result);
	}

	public function addNew($title, $content, $imgUrl, $userId) {
		$insertArticleSql = "INSERT into `articles` (`title`, `text_body`, `cover_uri`, `type`) VALUES(:title, :text_body, :cover_uri, 'article')";
		$articleId = $this -> upsert($insertArticleSql, array("title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		$linkToUserSql = "INSERT into `writers` (`article_id`, `user_id`) VALUES(:article_id, :user_id)";
		$this -> upsert($linkToUserSql, array("article_id" => $articleId, "user_id" => $userId));

		return null;
	}

	protected function toObjects($data) {
		$articles = array();
		$values = array_values($this -> articleMapping);
		for ($i = 0; $i < count($data); $i++) {
			$article = new Article();
			foreach ($values as $articleValue) {
				$res = array_search($articleValue, $this -> articleMapping);
				foreach (array($res) as $dbKey) {
					if (isset($data[$i][$dbKey]))
						$article -> {$this -> articleMapping[$dbKey]} = $data[$i][$dbKey];
				}
			}
			array_push($articles, $article);
		}
		return $articles;
	}

}
