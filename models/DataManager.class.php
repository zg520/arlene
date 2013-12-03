<?php
/**
 * The base class responsible for connecting to the database.
 * Since the project uses only one database source this could be an abstract class inherited by every data manager.
 *
 * @abstract
 * @package Common\Model\Managers
 */
abstract class DataManager {
	
	/**
	 * A holder for the database handle.
	 * 
	 * @access private
	 */
	private $handle;

	/**
	 * A function that executes the given SQL query and it's parameters.
	 * 
	 * @access protected
	 * @param string $sqlStmt The SQL query.
	 * @param string $params The parameters of the SQL Query. Optional with default value of null.
	 * @return array The result of the query.
	 */
	protected function query($sqlStmt, $params = null) {
		$this -> createHandle();

		$result = array();
		$sth = $this -> handle -> prepare($sqlStmt);
		$sth -> execute($params);
		$result = $sth -> fetchAll();

		$this -> closeHandle();

		return $result;
	}
	
	/**
	 * A function that executes the given SQL command(UPDATE, INSERT, DELETE) and it's parameters.
	 * 
	 * @access protected
	 * @param string $sqlStmt The SQL query.
	 * @param string $params The parameters of the SQL Query. Optional with default value of null.
	 * @return array The result of the query.
	 */
	protected function upsert($sqlStmt, $params = null) {
		$this -> createHandle();

		$result = array();
		$sth = $this -> handle -> prepare($sqlStmt);
		$sth -> execute($params);
		$result = $this->handle -> lastInsertId();

		$this -> closeHandle();

		return $result;
	}
	
		
	/**
	 * Takes an array with one object and returns the object only.
	 * 
	 * @access protected
	 * @return object The object in the array.
	 */
	protected function toSingleObject($objs) {
		if (count($objs) == 1) {
			return $objs[0];
		}
		return null;
	}
	
	/**
	 * Creates the DB handle.
	 * 
	 * @access private
	 * @return void
	 */
	private function createHandle() {
		try {
			$this -> handle = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
			if (DEVELOPMENT_ENVIRONMENT) {
				$this -> handle -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
		} catch(PDOException $exception) {
			error_log($exception -> getMessage());
		}
	}

	/**
	 * Destroys the DB handle.
	 * 
	 * @access private
	 * @return void
	 */
	private function closeHandle() {
		$this -> handle = null;
	}
}
