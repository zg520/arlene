<?php
class ObjectMapper{
	/**
	 * Mapping rules for creating article object.
	 * 
	 * @access private
	 * @var array
	 */
	private $articleMapping = array(
							'id' => 'id', 
							'title' => 'title', 
							'text_body' => 'body', 
							'cover_uri' => 'coverUrl', 
							'likes' => 'likes', 
							'dislikes' => 'dislikes', 
							'published_date' => 'publishDate',
							'create_time'=>'createdDate', 
							'user_id' => 'writers', 
							'status' => 'status');
	/**
	 * Mapping rules for creating column object.
	 * 
	 * @access private
	 * @var array
	 */
	private $columnMapping = array(
							'id' => 'id', 
							'title' => 'title', 
							'text_body' => 'body', 
							'cover_uri' => 'coverUrl', 
							'likes' => 'likes', 
							'dislikes' => 'dislikes', 
							'published_date' => 'publishDate',
							'create_time'=>'createdDate', 
							'user_id' => 'writers', 
							'status' => 'status');
							
	/**
	 * Mapping rules for creating review object.
	 * 
	 * @access private
	 * @var array
	 */
	private $reviewMapping = array(
							'id' => 'id', 
							'title' => 'title', 
							'text_body' => 'body', 
							'cover_uri' => 'coverUrl', 
							'likes' => 'likes', 
							'dislikes' => 'dislikes', 
							'published_date' => 'publishDate',
							'create_time'=>'createdDate', 
							'user_id' => 'writers', 
							'status' => 'status');
	/**
	 * Mapping rules for creating member object.
	 * 
	 * @access private
	 * @var array
	 */					
	private $memberMapping = array(
							'user_id' => 'userId', 
							'role'=> 'role');
							
	
	/**
	 * Mapping rules for creating comment object.
	 *
	 * @access private
	 * @var array
	 */	
	private $commentMapping = array(
							'user_id' => 'userId', 
							'date_posted' => 'datePublished', 
							'comment' => 'comment');
	
	public function toReviews($data){
		return $this->toObjects('review', $data);
	}
	
	public function toColumns($data){
		return $this->toObjects('column', $data);
	}

	public function toMembers($data){
		return $this->toObjects('member', $data);
	}
	
	public function toArticles($data){
		return $this-> toObjects('article', $data);
	}

	public function toComments($data){
		return $this-> toObjects('comment', $data);
	}

	private function toObjects($class, $data) {
		$objMapping = $this->{strtolower($class) . 'Mapping'};

		$objs = array();
		$values = array_values($objMapping);
		for ($i = 0; $i < count($data); $i++) {
			$className = ucfirst(strtolower($class));

			$obj = new $className();
			foreach ($values as $objValue) {
				$res = array_search($objValue, $objMapping);
				foreach (array($res) as $dbKey) {
					if (isset($data[$i][$dbKey])){
						$obj -> {$objMapping[$dbKey]} = $data[$i][$dbKey];
					}
				}
			}
			array_push($objs, $obj);
		}
		return $objs;
	}

}
