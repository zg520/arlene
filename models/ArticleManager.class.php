<?php
class ArticleManager extends DataManager {
	/**
	 * Mapping rules for creating article object.
	 *
	 * @var array
	 */
	public $articleMapping = array('id' => 'id', 'article_id' => 'id', 'title' => 'title', 'text_body' => 'body', 'cover_uri' => 'coverUrl', 'likes' => 'likes', 'dislikes' => 'dislikes', 'publish_date' => 'publishDate', 'writers' => 'writers', 'user_id' => 'writers', 'status' => 'status');

	public function getById($id) {

		$sql = "SELECT * FROM `full_articles` WHERE status = 'published' AND `id` = ?";
		$result = $this -> query($sql, array($id));
		return $this -> toSingleObject($result);
	}

	public function getNewest() {
		$sql = "SELECT * FROM `full_articles` WHERE status = 'published' ORDER BY `publish_date` DESC";
		$result = $this -> query($sql);
		return $this -> toObjects($result);
	}

	public function getWriterArticles($userId, $status) {
		$result = $this -> query("SELECT * FROM `writers` INNER JOIN `articles` ON `articles`.`id` = `writers`.`article_id` WHERE `user_id` = ? AND `status` = ?", array($userId, $status));

		return $this -> toObjects($result);
	}

	public function getAll() {
		$sql = "SELECT * FROM `full_articles` WHERE status = 'published'";
		$result = $this -> query($sql);
		return $this -> toObjects($result);
	}

	public function getRecommended() {
		$sql = "SELECT * FROM `full_articles` WHERE status = 'published' AND recommended = 'true'";
		$result = $this -> query($sql);
		return $this -> toObjects($result);
	}

	public function addNewArticle($title, $content, $imgUrl, $userId) {
		$insertArticleSql = "INSERT into `articles` (`title`, `text_body`, `cover_uri`, `type`) VALUES(:title, :text_body, :cover_uri, 'article')";
		$articleId = $this -> upsert($insertArticleSql, array("title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		echo $articleId;
		$linkToUserSql = "INSERT into `writers` (`article_id`, `user_id`) VALUES(:article_id, :user_id)";
		$result = $this -> upsert($linkToUserSql, array("article_id" => $articleId, "user_id" => $userId));
		echo $result;
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
