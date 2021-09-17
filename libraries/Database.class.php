<?php
/*
* PDO Database class
* Connects to database
* creates prepared statements, binds values,
* returns rows and results.
*/
class Database {
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASSWORD;
	private $dbname = DB_NAME;

	// database handler, statement, error.
	private $dbh;
	private $stmt;
	private $error;

	/*
	* Construct function
	* Sets options, and creates PDO instance.
	*/
	public function __construct(){
		$dsn = DB_DSN;
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		);
		try{
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		} catch(PDOException $e){
			$this->error = $e->getMessage();
			echo $this->error;
		}
	}

	/*
	* Prepares statement.
	*/
	public function query($sql){
		$this->stmt = $this->dbh->prepare($sql);
	}

	/*
	* Binds values; part of preventing SQL injection.
	*/
	public function bind($param, $value, $type = null){
		if (is_null($type)){
			switch(true){
				case is_int($value):
					$type= PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type= PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type= PDO::PARAM_NULL;
					break;
				default:
					$type= PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	/*
	* Executes statement. Effectively returns true or false; for inserting/updating/deleting.
	*/
	public function execute(){
		return $this->stmt->execute();
	}

	/*
	* Executes statement, returning an array of objects matching a select query.
	*/
	public function resultSet(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/*
	* Executes statement, returning a single row matching a select query.
	*/
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}

	/*
	* Returns the row count of matching records.
	*/
	public function rowCount(){
		return $this->stmt->rowCount();
	}
}
?>