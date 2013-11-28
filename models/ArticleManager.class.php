<?php
class ArticleManager extends DataManager {

	private $objMapper;
	public function __construct() {
		$this -> objMapper = new ObjectMapper();
	}

	/**
	 * Mapping rules for creating article object.
	 *
	 * @var array
	 */
	public function getById($id, $status = "published") {
		if ($status == null) {
			$sql = "SELECT * FROM `full_articles` WHERE `id` = :id";
			$params = array('id' => $id);
		} else {
			$sql = "SELECT * FROM `full_articles` WHERE status = :status AND `id` = :id";
			$params = array('id' => $id, 'status' => $status);
		}
		$result = $this -> query($sql, $params);

		if (count($result) > 0) {
			$article = $this -> toSingleObject($this -> objMapper -> toArticles($result));
			$article -> writers = $this -> getArticleWriters($article -> id);
			$article -> likes = $this -> getArticleLikesOrDislikes($article -> id, "positive");
			$article -> dislikes = $this -> getArticleLikesOrDislikes($article -> id, "negative");
			return $article;
		}
		return null;
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

	public function addEditorCommentToId($id, $editorId, $comment) {
		$insertArticleSql = "INSERT into `editorials` (`article_id`, `user_id`, `comment`) VALUES(:id, :editorId, :comment)";
		$articleId = $this -> upsert($insertArticleSql, array("articleId" => $id, "editorId" => $editorId, "comment" => $comment));
	}

	public function changeStatus($id, $editorId, $newStatus) {
		$insertArticleSql = "INSERT into `articles` WHERE `id` = :id (`status`) VALUES(:status)";
		$articleId = $this -> upsert($insertArticleSql, array("id" => $id, "status" => $newStatus));
	}

	public function getNewest($top = 5, $skip = 0) {
		$newest = array();
		$sql = "SELECT id FROM full_articles INNER JOIN publishmetadata ON id = article_id WHERE status = 'published' ORDER BY publishmetadata.published_date DESC LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		foreach ($result as $row) {
			array_push($newest, $this -> getById($row['id']));
		}
		return $newest;
	}

	public function getWriterArticles($userId, $status) {
		$result = $this -> query("SELECT * FROM `articlewriters` INNER JOIN `articles` ON `articles`.`id` = `articlewriters`.`article_id` WHERE `user_id` = ? AND `status` = ?", array($userId, $status));

		return $this -> objMapper -> toArticles($result);
	}

	public function getMostLiked($top = 5, $skip = 0) {
		$sql = "SELECT * FROM `full_articles` INNER JOIN articleLWHERE status = 'published' LIMIT " . $skip . ", " . $top;
		$mostLiked = array();
		$sql = "SELECT id FROM articles WHERE status = 'published' AND recommended = '1' LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		foreach ($result as $row) {
			array_push($mostLiked, $this -> getById($row['id']));
		}
		return $mostLiked;
	}

	public function getRecommended($top = 5, $skip = 0) {
		$recommended = array();
		$sql = "SELECT id FROM articles WHERE status = 'published' AND recommended = '1' LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		foreach ($result as $row) {
			array_push($recommended, $this -> getById($row['id']));
		}
		return $recommended;
	}

	public function getAll($status, $top = 5, $skip = 0) {
		$all = array();
		$sql = "SELECT id FROM `full_articles` WHERE status = :status LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql, array('status' => $status));
		foreach ($result as $row) {
			array_push($all, $this -> getById($row['id'], $status));
		}

		return $all;
	}

	public function addNew($title, $content, $imgUrl, $userId) {
		$insertArticleSql = "INSERT into `articles` (`id`, `title`, `text_body`, `cover_uri`, `type`) VALUES(:id, :title, :text_body, :cover_uri, 'article')";
		$articleId = Utility::generateArticleId($title);
		$this -> upsert($insertArticleSql, array("id" => $articleId, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		$linkToUserSql = "INSERT into `articlewriters` (`article_id`, `user_id`) VALUES(:article_id, :user_id)";
		$this -> upsert($linkToUserSql, array("article_id" => $articleId, "user_id" => $userId));

		return true;
	}

	public function getArticleWriters($id) {
		$writerSql = "SELECT `user_id` FROM `articlewriters` WHERE `article_id` = :articleId";
		$writers = $this -> query($writerSql, array('articleId' => $id));
		return $this -> objMapper -> toMembers($writers);
	}

	public function getArticleLikesOrDislikes($id, $voteType) {
		$sql = "SELECT Count(*) FROM `articlelikes` WHERE `article_id` = :articleId AND `vote` = :vote";
		$result = $this -> query($sql, array('articleId' => $id, 'vote' => $voteType));
		return $result[0][0];
	}

	public function vote($id, $userId, $voteType) {
		$sql = "SELECT vote FROM `articlelikes` WHERE `article_id` = :id AND `user_id` = :userId";
		$params = array('id' => $id, 'userId' => $userId);
		$result = $this -> query($sql, $params);
		
		$insertParams = array('id' => $id, 'userId' => $userId, 'vote' => $voteType);
		
		if (count($result[0]) == 0) {
			$voteSql = "INSERT into `articlelikes` (`article_id`, `user_id`, `vote`) VALUES(:id, :userId, :vote)";
			$this -> upsert($voteSql, $insertParams);
			return true;
		} else {
			if ($result[0][0] == $voteType) {
				return false;
			}
			$delete = "DELETE FROM `articlelikes` WHERE `article_id` = :id AND `user_id` = :userId";
			$this -> upsert($delete, $params);
			$insertSql = "INSERT into `articlelikes` (`article_id`, `user_id`, `vote`) VALUES(:id, :userId, :vote)";
			$result = $this -> upsert($insertSql, $insertParams);
			return true;
		}
	}

}
