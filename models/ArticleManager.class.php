<?php
class ArticleManager extends ContentManager {


	/**
	 * Fetches articles by id.
	 *
	 * @param string $id The article Id.
	 * @param string $status The status of the article. Optional parameter with default value of "published".
	 * @param bool $editorCommentsIncluded Whether to include editor comments when constructing the Article.
	 * @return Article The article from the database or empty.
	 */
	public function getArticleById($id, $status = "published", $editorCommentsIncluded = false) {
		$article = $this -> getById($id, "Article", $status, $editorCommentsIncluded);
		if(empty($article)){
			return false;
		}
		return $article;
	}

	public function getNewest($top = 5, $skip = 0) {
		$newest = $this -> getNewestIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getArticleById($row[0]));
		}
		return array_filter($results);
	}
	
	public function getPopular($top = 5, $skip = 0) {
		$newest = $this -> getMostPopularIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getArticleById($row[0]));
		}
		return array_filter($results);
	}
	
	public function getRecommended($top = 5, $skip = 0) {
		$newest = $this -> getRecommendedIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getArticleById($row[0]));
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
	public function updateArticle($id, $title, $content, $imgUrl){
		$insertArticleSql = "UPDATE `articles` SET `title` =:title ,`text_body` = :text_body, `cover_uri` =:cover_uri WHERE `id` = :id";
		$articleId = Utility::generateArticleId($title);
		$id = $this -> upsert($insertArticleSql, array("id" => $id, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		if($id != null){
			return true;
		}
		return false;
	}
	
	public function changeStatus($id, $editorId, $newStatus) {
		if($newStatus == "published"){
			$sql = "INSERT into `publishmetadata` (`article_id`, `user_id`) VALUES(:articleId, :editorId)";
			$this -> upsert($sql, array("articleId" => $id, "editorId" => $editorId));
		}else{
			$result = $this ->query("SELECT COUNT(*) FROM `publishmetadata` WHERE `article_id` = :article_id", 
								array('article_id' => $id));
			if($result[0][0] > 0){
			$this -> query("DELETE FROM `publishmetadata` WHERE `article_id` = :article_id", 
								array('article_id' => $id));
			}
		}
		$updateStatusSql = "UPDATE `articles` SET `status` = :status WHERE `id` = :id";
		$articleId = $this -> upsert($updateStatusSql, array("id" => $id, "status" => $newStatus));

		if($articleId != null){
			return true;
		}
		return false;
	}
	
	public function changeRecommendedStatus($id, $isRecommended) {
		$result = $this ->query("SELECT `recommended` FROM `articles` WHERE `id` = :article_id", 
								array('article_id' => $id));
		if($result[0]['recommended'] != $isRecommended){
			$updateStatusSql = "UPDATE `articles` SET `recommended` = :recommended WHERE `id` = :article_id";
			$articleId = $this -> upsert($updateStatusSql, array("article_id" => $id, "recommended" => $isRecommended));

			if($articleId != null){
				return true;
			}
		}
		return false;
	}
		
	/**
	 * Adds an editor comment to an article.
	 * 
	 * @access public
	 * @param string $id The id of the article to add a comment to.
	 * @param string $userId The id of the user adding the comment.
	 * @param string $comment The comment to be added to the article.
	 * @return true if the operation was successful
	 */
	public function addEditorCommentToId($id, $editorId, $comment) {
		$sql = "INSERT into `articleedits` (`article_id`, `user_id`, `comment`) VALUES(:articleId, :editorId, :comment)";
		$commentId= $this -> upsert($sql, array("articleId" => $id, "editorId" => $editorId, "comment" => $comment));
		if ($commentId != null) {
			return true;
		}

		return false;
	}
	
	/**
	 * Adds a user comment to an article.
	 * 
	 * @access public
	 * @param string $id The id of the article to add a comment to.
	 * @param string $userId The id of the user adding the comment.
	 * @param string $comment The comment to be added to the article.
	 * @return true if the operation was successful
	 */
	public function addUserCommentToId($id, $userId, $comment) {
		$sql = "INSERT into `comments` (`article_id`, `user_id`, `comment`) VALUES(:articleId, :userId, :comment)";
		$commentId = $this -> upsert($sql, array("articleId" => $id, "userId" => $userId, "comment" => $comment));
		if ($commentId != null) {
			return true;
		}

		return false;
	}
	
	public function addNew($title, $content, $imgUrl, $userId) {
		$insertArticleSql = "INSERT into `articles` (`id`, `title`, `text_body`, `cover_uri`, `type`) VALUES(:id, :title, :text_body, :cover_uri, 'article')";
		$articleId = Utility::generateArticleId($title);
		$this -> upsert($insertArticleSql, array("id" => $articleId, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		$linkToUserSql = "INSERT into `articlewriters` (`article_id`, `user_id`) VALUES(:article_id, :user_id)";
		$this -> upsert($linkToUserSql, array("article_id" => $articleId, "user_id" => $userId));

		return true;
	}
	
	/**
	 * Gets the articles associated with a writer by using their status.
	 * 
	 * @param string $userId The user id.
	 * @param string $status The article status.
	 * @return array The articles with specific status associated with an user.
	 */
	public function getWriterArticles($userId, $status) {
		$result = $this -> query("SELECT * FROM `articlewriters` INNER JOIN `articles` ON `articles`.`id` = `articlewriters`.`article_id` WHERE `user_id` = ? AND `status` = ?", 
								array($userId, $status));
		return $this -> objMapper -> toArticles($result);
	}

	public function getMostLiked($top = 5, $skip = 0) {
		$sql = "SELECT * FROM `full_articles` INNER JOIN articleLikes WHERE status = 'published' LIMIT " . $skip . ", " . $top;
		$mostLiked = array();
		$sql = "SELECT id FROM articles WHERE status = 'published' AND recommended = '1' LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		foreach ($result as $row) {
			array_push($mostLiked, $this -> getById($row['id']));
		}
		return $mostLiked;
	}

	
	/**
	 * Gets the editors comments of the article.
	 *
	 * @param string $id The article id.
	 * @return array User comments related to the article.
	 */
	public function getEditorComments($id) {
		$commentSql = "SELECT `user_id`, `comment`, `date_posted` FROM `articleedits` WHERE `article_id` = :articleId ORDER BY `date_posted` DESC";
		$comments = $this -> query($commentSql, array('articleId' => $id));
		return $this -> objMapper -> toComments($comments);
	}
	
	/**
	 * Gets the comments of the article.
	 *
	 * @param string $id The article id.
	 * @return array User comments related to the article.
	 */
	public function getArticleComments($id) {
		$commentSql = "SELECT `user_id`, `comment`, `date_posted` FROM `comments` WHERE `article_id` = :articleId ORDER BY `date_posted` DESC";
		$comments = $this -> query($commentSql, array('articleId' => $id));
		return $this -> objMapper -> toComments($comments);
	}

	/**
	 * Gets the number of article's likes or dislikes.
	 *
	 * @access public
	 * @param string $id The id of the article.
	 * @param string $voteType The type of vote. "Positive" or "Negative".
	 * @return int The number of likes or dislikes.
	 */
	public function getVotes($id, $voteType) {
		$sql = "SELECT Count(*) FROM `articlelikes` WHERE `article_id` = :articleId AND `vote` = :vote";
		$result = $this -> query($sql, array('articleId' => $id, 'vote' => $voteType));
		return $result[0][0];
	}

	/**
	 * Adds a user vote to an article.
	 *
	 * @access public
	 * @param string $id The id of the article.
	 * @param string $userId The id of the user.
	 * @param string $voteType The type of vote to be added to the article.
	 * @return true if the operation was successful.
	 */
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
