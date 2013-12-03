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
			array_push($results, $this -> getArticleById($row['id'], $status));
		}
		return array_filter($results);
	}
	
	public function getAllArticles($status){
		$all = $this -> getAllIds($status, "article");
		$results = array();
		foreach ($all as $row) {
			array_push($results, $this -> getArticleById($row[0], $status));
		}
		return array_filter($results);
	}
	
	public function update($id, $title, $content, $imgUrl){
		$insertArticleSql = "UPDATE `articles` SET `title` =:title ,`text_body` = :text_body, `cover_uri` =:cover_uri WHERE `id` = :id";
		$id = $this -> upsert($insertArticleSql, array("id" => $id, "title" => $title, "text_body" => $content, "cover_uri" => $imgUrl));
		if($id != null){
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
}
