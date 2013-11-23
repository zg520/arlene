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
	
	public function getPendingWriterArticles($userId) {
		$result = $this -> query("SELECT * FROM `writers` INNER JOIN `articles` ON `articles`.`id` = `writers`.`article_id` WHERE `user_id` = ? AND `status` <> 'published'", array($userId));
		
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

	protected function toObjects($data) {
		$articles = array();
		$values = array_values($this -> articleMapping);
		for ($i = 0; $i < count($data); $i++) {
			$article = new Article();
			foreach ($values as $articleValue) {
				$res = array_search($articleValue, $this -> articleMapping);
				foreach (array($res) as $dbKey) {
					if (isset($data[$i][$dbKey]))
						$article -> {$this->articleMapping[$dbKey]} = $data[$i][$dbKey];
				}
			}
			array_push($articles, $article);
		}
		return $articles;
	}

}
