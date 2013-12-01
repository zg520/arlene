<?php
/**
 * A class providing base functionality for Article, Column and Review management.
 *
 * @package default
 * @author Y6187553
 */
abstract class ContentManager extends DataManager {

	private $objMapper;
	
   /**
	* Default constructor creating an ObjectMapper class.
	*
	*/
	public function __construct() {
		$this -> objMapper = new ObjectMapper();
	}
	
	/**
	 * Gets an article for user.
	 * 
	 * @access public
	 * @param string $id The id of the article.
	 * @param string $userId The id of the user.
	 * @return true the Content.
	 */
	public abstract function getForUserById($id, $user);
	
	/**
	 * Gets the writers of the article.
	 *
	 * @param string $id The article id.
	 * @return array Members elected as writers of the article.
	 */
	public function getArticleWriters($id) {
		$writerSql = "SELECT `user_id` FROM `articlewriters` WHERE `article_id` = :articleId";
		$writers = $this -> query($writerSql, array('articleId' => $id));
		return $this -> objMapper -> toMembers($writers);
	}
		
	/**
	 * Changes the status of an article.
	 * 
	 * @access public
	 * @param string $id The id of the article to add a comment to.
	 * @param string $editorId The id of the editor changing the status.
	 * @param string $newStatus The new status.
	 * @return true if the operation was successful
	 */
	public function changeStatus($id, $editorId, $newStatus) {
		if($newStatus == "published"){
			$sql = "INSERT into `publishmetadata` (`article_id`, `user_id`) VALUES(:articleId, :editorId)";
			$this -> upsert($sql, array("articleId" => $id, "editorId" => $editorId));
		}else{
			$this -> query("DELETE FROM `publishmetadata` WHERE `article_id` = :article_id", 
								array('article_id' => $id));
		}
		$updateStatusSql = "UPDATE `articles` SET `status` WHERE `id` = :id";
		$articleId = $this -> upsert($updateStatusSql, array("id" => $id, "status" => $newStatus));

		if($articleId != null){
			return true;
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
	
	/**
	 * Fetches bare bone articles.
	 *
	 * @param string $id The article Id.
	 * @param string $status The status of the article. Optional parameter with default value of "published".
	 * @param bool $editorCommentsIncluded Whether to include editor comments when constructing the Article.
	 * @return Article The article from the database or null.
	 */
	protected function getById($id, $status = "published", $editorCommentsIncluded = false) {
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
			$article -> publicComments = $this -> getPublicComments($article -> id);
			$article -> likes = $this -> getVotes($article -> id, "positive");
			$article -> dislikes = $this -> getVotes($article -> id, "negative");
			if($editorCommentsIncluded){
				$article -> editorComments = $this -> getEditorComments($article -> id);
			}
			return $article;
		}
		return null;
	}
	
	/**
	 * Fetches the ids of the newest articles.
	 *
	 * @param int $top How many to take.
	 * @param int $skip How many to skip.
	 * @return array Article ids.
	 */
	protected function getNewestIds($top = 5, $skip = 0) {
		$newest = array();
		$sql = "SELECT id FROM `articles` INNER JOIN publishmetadata ON id = article_id WHERE status = 'published' ORDER BY publishmetadata.published_date DESC LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		return $result[0];
	}

	/**
	 * Gets the articles associated with a writer by using their status.
	 * 
	 * @param string $userId The user id.
	 * @param string $status The article status.
	 * @return array The articles with specific status associated with an user.
	 */
	protected function getWriterArticlesIds($userId, $status) {
		$result = $this -> query("SELECT `id` FROM `articlewriters` INNER JOIN `articles` ON `articles`.`id` = `articlewriters`.`article_id` WHERE `user_id` = ? AND `status` = ?", 
								array($userId, $status));
		return $result[0];
	}

	protected function getMostPopularIds($top = 5, $skip = 0) {
		$sql = "SELECT `id`, COUNT(`vote`) as `rating` FROM `articles` Right JOIN articlelikes on `articles`.`id` = `articlelikes`.`article_id` WHERE status = 'published' group by `article_id` ORDER BY `rating` DESC LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		return $result[0];
	}

	protected function getRecommendedIds($top = 5, $skip = 0) {
		$recommended = array();
		$sql = "SELECT id FROM articles WHERE status = 'published' AND recommended = '1' LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);

		return $result[0];
	}

	protected function getAll($status, $type, $top = 5, $skip = 0) {
		$sql = "SELECT id FROM `articles` WHERE status = :status AND `type` = :type LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql, array('status' => $status, 'type' => $type));
		return $result[0];
	}
	
	/**
	 * Gets the editors comments of the article.
	 *
	 * @param string $id The article id.
	 * @return array User comments related to the article.
	 */
	protected function getEditorComments($id) {
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
	protected function getPublicComments($id) {
		$commentSql = "SELECT `user_id`, `comment`, `date_posted` FROM `comments` WHERE `article_id` = :articleId ORDER BY `date_posted` DESC";
		$comments = $this -> query($commentSql, array('articleId' => $id));
		return $this -> objMapper -> toComments($comments);
	}

	/**
	 * Gets the number of article's likes or dislikes.
	 *
	 * @access protected
	 * @param string $id The id of the article.
	 * @param string $voteType The type of vote. "Positive" or "Negative".
	 * @return int The number of likes or dislikes.
	 */
	protected function getVotes($id, $voteType) {
		$sql = "SELECT Count(*) FROM `articlelikes` WHERE `article_id` = :articleId AND `vote` = :vote";
		$result = $this -> query($sql, array('articleId' => $id, 'vote' => $voteType));
		return $result[0][0];
	}

	/**
	 * Adds a user vote to an article.
	 *
	 * @access protected
	 * @param string $id The id of the article.
	 * @param string $userId The id of the user.
	 * @param string $voteType The type of vote to be added to the article.
	 * @return true if the operation was successful.
	 */
	protected function vote($id, $userId, $voteType) {
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
