<?php
class ArticleManager extends DataManager{
	/**
	 * Mapping rules for creating article object.
	 *
	 * @var array
	 */
	public $articleMapping = array(
				'id' => 'id',
				'title' => 'title',
	 			'body' => 'text_body',
				'coverUrl' => 'cover_uri',
				'likes' => 'likes',
				'dislikes' => 'dislikes',
 				'publishDate' => 'publish_date',
				'writers' => 'writers');
	
	public function getAll(){
		
		/*** The SQL SELECT statement ***/
    	$sql = "SELECT * FROM `full_articles` WHERE status = 'published'";
		 /*** fetch into an PDOStatement object ***/
    	$result = $this->query($sql);
		
		return $this->toObjects($result);
	}
	public function getRecommended(){
		/*** The SQL SELECT statement ***/
    	$sql = "SELECT * FROM `full_articles` WHERE status = 'published' AND recommended = 'true'";
    	$result = $this->query($sql);

		return $this->toObjects($result);
	}
	private function toObjects($data){
		
		$articles = array();
		$values = array_keys($this->articleMapping);
		
		 for($i = 0; $i < count($data); $i++){
		 	$article = new Article();
			 foreach ($values as $articleValue) {
				 $article->{$articleValue} = $data[$i][$this->articleMapping[$articleValue]];
			 }
			 array_push($articles, $article);
		 }
		 return $articles;
	}
}