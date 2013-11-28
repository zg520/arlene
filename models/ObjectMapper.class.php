<?php
class ObjectMapper{
	/**
	 * Mapping rules for creating article object.
	 *
	 * @var array
	 */
	private $articleMapping = array('id' => 'id', 'article_id' => 'id', 'title' => 'title', 'text_body' => 'body', 'cover_uri' => 'coverUrl', 'likes' => 'likes', 'dislikes' => 'dislikes', 'publish_date' => 'publishDate', 'user_id' => 'writers', 'status' => 'status');
	private $memberMapping = array('id'=>'userId', 'user_id'=>'userId', 'role'=> 'role');
	private $reviewMapping = array();
	
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
