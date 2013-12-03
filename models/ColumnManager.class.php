<?php
/**
 * A class to Manage the interaction between
 * the data source and the application's @see Column objects.
 * Extends @See CoontentManager.
 *
 * @access default
 * @package Common\Model\Managers
 */
class ColumnManager extends ContentManager {

	/**
	 * Fetches single Column.
	 *
	 * @access public
	 * 
	 * @param string $id The column Id.
	 * @param string $status The status of the Column. Optional parameter with default value of "published".
	 * @param bool $editorCommentsIncluded Whether to include editor comments when constructing the Column. Default is false.
	 * 
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

	/**
	 * Fetches all columns from a list with the newest content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Columns or empty .
	 */
	public function getNewest($top = 5, $skip = 0) {
		$newest = $this -> getNewestIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getColumnById($row[0]));
		}
		return array_filter($results);
	}
	
	/**
	 * Fetches all columns from a list with the most popular content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Columns or empty .
	 */
	public function getPopular($top = 5, $skip = 0) {
		$newest = $this -> getMostPopularIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getColumnById($row[0]));
		}
		return array_filter($results);
	}
	
	/**
	 * Fetches all columns from a list with recommended content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Columns or empty .
	 */
	public function getRecommended($top = 5, $skip = 0) {
		$newest = $this -> getRecommendedIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getColumnById($row[0]));
		}
		return array_filter($results);
	}
	
	/**
	 * Gets the all the columns by using their status.
	 *
	 * @access public
	 *
	 * @param string $status The column status.
	 * @return array The columns with specific status associated with an user.
	 */
	public function getAllColumns($status){
		$all = $this -> getAllIds($status, "column");
		$results = array();
		foreach ($all as $row) {
			array_push($results, $this -> getColumnById($row[0], $status));
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
	public function getWriterContent($userId, $status){
		$content = $this -> getWriterArticlesIds($userId, $status);

		$results = array();
		foreach ($content as $row) {
			array_push($results, $this -> getColumnById($row[0], $status));
		}
		return array_filter($results);
	}
	/**
	 * Gets all valid topics for columns.
	 *
	 * @access public
	 *
	 * @return array The topics as strings.
	 */
	public function getTopics(){
		$topicsSql = "SELECT * FROM `columntopics`";
		$topics = $this -> query($topicsSql);
		$topicsArray = array();
		foreach ($topics as $topic) {
			array_push($topicsArray, $topic['topic']);
		}
		return $topicsArray;
	}
	
	/**
	 * Updates a column.
	 *
	 * @access public
	 *
	 * @param string $id The column id.
	 * @param string $title The column title.
	 * @param string $content The column text body content.
	 * @param string $topic The column topic.
	 * @param string $imgUrl The column image url.
	 *
	 * @return bool True if the operation was successful, false otherwise.
	 */
	public function update($id, $title, $content, $imgUrl, $topic){
		$updateSql = "UPDATE `articles` SET `title` =:title ,`text_body` = :text_body, `cover_uri` =:cover_uri WHERE `id` = :id";
		$id = $this -> upsert($updateSql, array("id" => $id, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		if($id == null){
			return false;
		}
		$updateColSql = "UPDATE `columnarticles` SET `topic` =:topic WHERE `article_id` = :id";
		$colId = $this -> upsert($updateSql, array("id" => $id, "topic" => $topic));
		if($id == null){
			return false;
		}
		return true;
	}
	
	/**
	 * Adds a column.
	 *
	 * @access public
	 *
	 * @param string $title The column title.
	 * @param string $content The column text body content.
	 * @param string $imgUrl The column image url.
	 * @param string $topic The column topic.
	 * @param string $userId The writer id.
	 *
	 * @return bool True if the operation was successful, false otherwise.
	 */
	public function addNew($title, $content, $imgUrl, $topic, $userId) {
		try{
			$id = Utility::generateArticleId($title);
			$insertArticleSql = "INSERT into `articles` (`id`, `title`, `text_body`, `cover_uri`, `type`) VALUES(:id, :title, :text_body, :cover_uri, 'column')";
			$articleId = $this -> upsert($insertArticleSql, array("id" => $id, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
			$insertColumnSql = "INSERT into `columnarticles` (`article_id`, `topic`) VALUES(:article_id, :topic)";
			$this -> upsert($insertColumnSql, array("article_id" => $id, "topic" => $topic));
			$linkToUserSql = "INSERT into `articlewriters` (`article_id`, `user_id`) VALUES(:article_id, :user_id)";
			$this -> upsert($linkToUserSql, array("article_id" => $id, "user_id" => $userId));
			return true;
		}catch(Exception $e){
			return false;
		}
	}
}
