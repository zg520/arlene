<?php
/**
 * A class to Manage the interaction between
 * the data source and the application's @see Article objects.
 * Extends @See CoontentManager.
 *
 * @access default
 * @package Common\Model\Managers
 */
class ArticleManager extends ContentManager {

	/**
	 * Fetches single article.
	 *
	 * @access public
	 *
	 * @param string $id The article Id.
	 * @param string $status The status of the article. Optional parameter with default value of "published".
	 * @param bool $editorCommentsIncluded Whether to include editor comments when constructing the Article. Default value false.
	 * @return Article The article from the database or empty.
	 */
	public function getArticleById($id, $status = "published", $editorCommentsIncluded = false) {
		$article = $this -> getById($id, "Article", $status, $editorCommentsIncluded);
		if (empty($article)) {
			return false;
		}
		return $article;
	}

	/**
	 * Fetches all articles from a list with the newest content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Articles or empty .
	 */
	public function getNewest($top = 5, $skip = 0) {
		$newest = $this -> getNewestIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getArticleById($row[0]));
		}
		return array_filter($results);
	}

	/**
	 * Fetches all articles from a list with the most popular content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Articles or empty .
	 */
	public function getPopular($top = 5, $skip = 0) {
		$newest = $this -> getMostPopularIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getArticleById($row[0]));
		}
		return array_filter($results);
	}

	/**
	 * Fetches all articles from a list with recommended content in the range specified.
	 * The default range is 0 - 5 content pieces.
	 *
	 * @access public
	 *
	 * @param int $top The total number of content to check for articles.
	 * @param int $skip The number of content to skip before checking for articles.
	 * @return array Array of @see Articles or empty .
	 */
	public function getRecommended($top = 5, $skip = 0) {
		$newest = $this -> getRecommendedIds($top, $skip);
		$results = array();
		foreach ($newest as $row) {
			array_push($results, $this -> getArticleById($row[0]));
		}
		return array_filter($results);
	}

	/**
	 * Gets the all the articles by using their status.
	 *
	 * @access public
	 *
	 * @param string $status The article status.
	 * @return array The articles with specific status associated with an user.
	 */
	public function getAllArticles($status) {
		$all = $this -> getAllIds($status, "article");
		$results = array();
		foreach ($all as $row) {
			array_push($results, $this -> getArticleById($row[0], $status));
		}
		return array_filter($results);
	}
	
	/**
	 * Gets the content associated with a writer by using their status.
	 *
	 * @access public
	 *
	 * @param string $userId The user id.
	 * @param string $status The article status.
	 * @return array The articles with specific status associated with an user.
	 */
	public function getWriterContent($userId, $status) {
		$content = $this -> getWriterArticlesIds($userId, $status);
		$results = array();
		foreach ($content as $row) {
			array_push($results, $this -> getArticleById($row['id'], $status));
		}
		return array_filter($results);
	}

	/**
	 * Updates an article.
	 *
	 * @access public
	 *
	 * @param string $id The article id.
	 * @param string $title The article title.
	 * @param string $content The article text body content.
	 * @param string $imgUrl The article image url.
	 *
	 * @return bool True if the operation was successful, false otherwise.
	 */
	public function update($id, $title, $content, $imgUrl) {
		$insertArticleSql = "UPDATE `articles` SET `title` =:title ,`text_body` = :text_body, `cover_uri` =:cover_uri WHERE `id` = :id";
		$id = $this -> upsert($insertArticleSql, array("id" => $id, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		if ($id != null) {
			return true;
		}
		return false;
	}

	/**
	 * Adds an article.
	 *
	 * @access public
	 *
	 * @param string $title The article title.
	 * @param string $content The article text body content.
	 * @param string $imgUrl The article image url.
	 * @param string $userId The writer id.
	 *
	 * @return bool True if the operation was successful, false otherwise.
	 */
	public function addNew($title, $content, $imgUrl, $userId) {
		$insertArticleSql = "INSERT into `articles` (`id`, `title`, `text_body`, `cover_uri`, `type`) VALUES(:id, :title, :text_body, :cover_uri, 'article')";
		$articleId = Utility::generateArticleId($title);
		$this -> upsert($insertArticleSql, array("id" => $articleId, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		$linkToUserSql = "INSERT into `articlewriters` (`article_id`, `user_id`) VALUES(:article_id, :user_id)";
		$this -> upsert($linkToUserSql, array("article_id" => $articleId, "user_id" => $userId));

		return true;
	}

}
