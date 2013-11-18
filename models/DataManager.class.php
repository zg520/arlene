<?php
/**
 * The base class responsible for connecting to the database.
 *
 * @package default
 * @author  ...
 */
abstract class DataManager {
	
	private $handle;
	
	protected function query($sqlStmt){
		$this->createHandle();
		
		$result = array();
		$sth = $this->handle->prepare($sqlStmt);
		$sth->execute();
		$result = $sth->fetchAll();
		
		$this->closeHandle();
		
		return $result;
	}   
	
	 protected function createHandle() {
    	try{
        	$this->handle = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
			if(DEVELOPMENT_ENVIRONMENT){
				$this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			}
        }catch(PDOException $exception){
        	error_log($exception->getMessage());
        }
    }
	protected function closeHandle(){
		$this->handle = null;
	}
}