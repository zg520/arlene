<?php
class ContentSorter {
	
	/**
	 * Prevents the CurrentUser class of being instantiated.
	 */
	private function __construct() {
	}
	
	public static function byPublishDateDesc($content){
			uksort($content, function($a, $b){
				if(empty($a -> publishDate) || empty($a -> publishDate)){
					return 0;
				}
				return ($a -> publishDate > $b -> publishDate) ? -1 : 1;
			});
		return $content;
	}
}