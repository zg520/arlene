<?php
/**
 * The base class responsible for connecting to the database.
 * Since the project uses only one database source this could be an abstract class inherited by every data manager.
 *
 * @author  ...
 */
abstract class DataManager {

	private $handle;

	protected function query($sqlStmt, $params = null) {
		$this -> createHandle();

		$result = array();
		$sth = $this -> handle -> prepare($sqlStmt);
		$sth -> execute($params);
		$result = $sth -> fetchAll();

		$this -> closeHandle();

		return $result;
	}
	
	protected function upsert($sqlStmt, $params = null) {
		$this -> createHandle();

		$result = array();
		$sth = $this -> handle -> prepare($sqlStmt);
		$sth -> execute($params);
		$result = $this->handle -> lastInsertId();

		$this -> closeHandle();

		return $result;
	}
	protected function createHandle() {
		try {
			$this -> handle = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
			if (DEVELOPMENT_ENVIRONMENT) {
				$this -> handle -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
		} catch(PDOException $exception) {
			error_log($exception -> getMessage());
		}
	}

	protected function closeHandle() {
		$this -> handle = null;
	}

	protected function toSingleObject($objs) {
		if (count($objs) == 1) {
			return $objs[0];
		}
		return null;
	}
}
