<?php
 
class DbHandler {
    public $_dbHandle;
 
    /** 
	 * Connects to database 
	 **/
    function connect() {
    	try{
        	$this->_dbHandle = new PDO("mysql:host=" + DB_HOST + ";dbname=" + DB_NAME, DB_USER, DB_PASSWORD);
			if(DEVELOPMENT_ENVIRONMENT){
				$_dbHandle->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			}
        }catch(PDOException $exception){
        	error_log($e->getMessage());
        }
    }
 
    /** 
	 * Close the connection 	
	 **/
    function disconnect() {
    	$this->_dbHandle = null;
    }
}