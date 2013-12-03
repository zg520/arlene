<?php
/**
 * A class providing base functionality for Article, Column and Review management.
 *
 * @author Y6187553
 */
abstract class ContentManager extends DataManager {
	
	/**
	 * Private object responsible for creating other objects.
	 */
	private $objMapper;
	
   /**
	* Default constructor creating an ObjectMapper class.
	*
	*/
	public function __construct() {
		$this -> objMapper = new ObjectMapper();
	}
	
	/**
	 * Gets the content associated with a writer by using their status.
	 * 
	 * @param string $userId The user id.
	 * @param string $status The article status.
	 * @return array The articles with specific status associated with an user.
	 */
	public abstract function getWriterContent($userId, $status);
	
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
		$this -> upsert("DELETE FROM `publishmetadata` WHERE `article_id` = :article_id", 
								array('article_id' => $id));
		if($newStatus == "published"){
			$sql = "INSERT into `publishmetadata` (`article_id`, `user_id`) VALUES(:articleId, :editorId)";
			$this -> upsert($sql, array("articleId" => $id, "editorId" => $editorId));
		}
		$updateStatusSql = "UPDATE `articles` SET `status` = :status WHERE `id` = :id";
		$contentId = $this -> upsert($updateStatusSql, array("id" => $id, "status" => $newStatus));

		if($contentId != null){
			return true;
		}
		return false;
	}
	
	/**
	 * Changes the recommended flag of a content piece.
	 * 
	 * @access public
	 * @param string $id The id of the article to add a comment to.
	 * @param bool $isRecommended The whether the content is recommended.
	 * @return true if the operation was successful
	 */
	public function changeRecommendedStatus($id,$isRecommended) {
		$updateStatusSql = "UPDATE `articles` SET `recommended` = :recommended WHERE `id` = :id";
		$contentId = $this -> upsert($updateStatusSql, array("id" => $id, "recommended" => $isRecommended));

		if($contentId != null){
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
	 * @param string $contentType The type of content with first letter capitalised and singular.
	 * @param string $status The status of the article. Optional parameter with default value of "published".
	 * @param bool $editorCommentsIncluded Whether to include editor comments when constructing the Article.
	 * @return Article The article from the database or null.
	 */
	protected function getById($id, $contentType, $status = "published", $editorCommentsIncluded = false) {
		if ($status == null) {
			$sql = "SELECT * FROM `articles` WHERE `id` = :id and `type` =:type";
			$params = array('id' => $id, 'type' => strtolower($contentType));
		} else if($status == "published"){
			$sql = "SELECT * FROM `articles` INNER JOIN `publishmetadata` on `id` = `article_id` WHERE status = :status AND `id` = :id and `type` =:type";
			$params = array('id' => $id, 'status' => $status, 'type' => strtolower($contentType));
		}
		else{
			$sql = "SELECT * FROM `articles` WHERE status = :status AND `id` = :id and `type` =:type";
			$params = array('id' => $id, 'status' => $status, 'type' => strtolower($contentType));
		}
		$result = $this -> query($sql, $params);

		if (count($result) > 0) {
			$content = $this -> toSingleObject($this -> objMapper -> {"to" . $contentType . "s"}($result));
			$content -> writers = $this -> getArticleWriters($content -> id);
			$content -> publicComments = $this -> getPublicComments($content -> id);
			$content -> likes = $this -> getVotes($content -> id, "positive");
			$content -> dislikes = $this -> getVotes($content -> id, "negative");
			if($editorCommentsIncluded){
				$content -> editorComments = $this -> getEditorComments($content -> id);
			}
			return $content;
		}
		return false;
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
		$sql = "SELECT `id` FROM `articles` INNER JOIN publishmetadata ON `articles`.`id` = `publishmetadata`.`article_id` WHERE status = 'published' ORDER BY publishmetadata.published_date DESC LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		return $result;
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
		return $result;
	}

	protected function getMostPopularIds($top = 5, $skip = 0) {
		$sql = "SELECT `id`, COUNT(`vote`) as `rating` FROM `articles` Right JOIN articlelikes on `articles`.`id` = `articlelikes`.`article_id` WHERE status = 'published' group by `article_id` ORDER BY `rating` DESC LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);
		return $result;
	}

	protected function getRecommendedIds($top = 5, $skip = 0) {
		$recommended = array();
		$sql = "SELECT id FROM articles WHERE status = 'published' AND recommended = '1' LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql);

		return $result;
	}

	protected function getAllIds($status, $type, $top = 5, $skip = 0) {
		$sql = "SELECT id FROM `articles` WHERE status = :status AND `type` = :type LIMIT " . $skip . ", " . $top;
		$result = $this -> query($sql, array('status' => $status, 'type' => $type));
		return $result;
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
