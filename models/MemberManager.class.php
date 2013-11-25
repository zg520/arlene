<?php
class MemberManager extends DataManager {
	/**
	 * Mapping rules for creating Member objects.
	 *
	 * @var array
	 */
	public $memberMapping = array('userId' => 'id', 'role' => 'role');

	public function authenticateMember($member) {
		$result = $this -> query("SELECT `id`, `role` FROM `users` WHERE `id` = ? AND `password` = ?", array($member -> userId, $member -> password));
		return $this -> toSingleObject($result);
	}

	protected function toObjects($data) {

		$members = array();
		$values = array_keys($this -> memberMapping);

		for ($i = 0; $i < count($data); $i++) {
			$member = new Member();
			foreach ($values as $memberValue) {
				$member -> {$memberValue} = $data[$i][$this -> memberMapping[$memberValue]];
			}
			$member-> authenticate();
			array_push($members, $member);
		}
		return $members;
	}

}
