<?php
/**
 * MySQL Database Connection Class
 * @access public
 * @package SPLIB
 */
class MySQL {
	/**
	 * MySQL server hostname
	 * @access private
	 * @var string
	 */
	var $host;

	/**
	 * MySQL username
	 * @access private
	 * @var string
	 */
	var $dbUser;

	/**
	 * MySQL user's password
	 * @access private
	 * @var string
	 */
	var $dbPass;

	/**
	 * Name of database to use
	 * @access private
	 * @var string
	 */
	var $dbName;

	/**
	 * MySQL Resource link identifier stored here
	 * @access private
	 * @var string
	 */
	var $dbConn;

	/**
	 * Stores error messages for connection errors
	 * @access private
	 * @var string
	 */
	var $connectError;

	var $dbType;
	/**
	 * MySQL constructor
	 * @param string host (MySQL server hostname)
	 * @param string dbUser (MySQL User Name)
	 * @param string dbPass (MySQL User Password)
	 * @param string dbName (Database to select)
	 * @access public
	 */
	function MySQL ($host,$dbUser,$dbPass,$dbName, $dbType="UTF-8") {
		$this->host=$host;
		$this->dbUser=$dbUser;
		$this->dbPass=$dbPass;
		$this->dbName=$dbName;
		$this->dbType=$dbType;
		$this->connectToDb();
	}

	/**
	 * Establishes connection to MySQL and selects a database
	 * @return void
	 * @access private
	 */
	function connect () {
		// Make connection to MySQL server
		if (!$this->dbConn = @mysql_connect($this->host,$this->dbUser,$this->dbPass)) {
			trigger_error('Could not connect to server');
			$this->connectError=true;
			// Select database
		} else if ( !@mysql_select_db($this->dbName,$this->dbConn) ) {
			// Create database
			if (!@mysql_query("CREATE DATABASE ".$this->dbName,$this->dbConn)) {
				// unable to create? Stop with errors
				trigger_error('Could not select database');
				$this->connectError=true;
			}
		}
		//$this->query("SET CHARACTER SET '$this->dbType'");
	}

	function connectToDb() {
		$this->dbConn = @mysql_connect($this->host,$this->dbUser,$this->dbPass);
		if (!$this->dbConn || !mysql_select_db($this->dbName, $this->dbConn)) {
			if(!defined(DB_USER)) {
				trigger_error("No DB_USER set, can't continue");
			}
			$this->dbConn = mysql_connect($this->host,DB_USER,DB_PASSWORD);
			if (!@mysql_query("CREATE DATABASE ".$this->dbName,$this->dbConn)) {
				// unable to create? Stop with errors
				trigger_error('Could not select database');
				$this->connectError=true;
			}
			if($this->dbUser != DB_USER ) {
				@$this->query("REVOKE ALL ON *.* FROM '$this->dbUser'@'localhost'");
				$this->query("GRANT ALL ON $this->dbName.* TO '$this->dbUser'@'localhost' IDENTIFIED BY '$this->dbPass'");
			}
			if (!$this->dbConn = @mysql_connect($this->host,$this->dbUser,$this->dbPass)) {
				trigger_error('Could not connect to server');
				$this->connectError=true;
				// Select database
			} else if ( !@mysql_select_db($this->dbName,$this->dbConn) ) {
				// Create database
				if (!@mysql_query("CREATE DATABASE ".$this->dbName,$this->dbConn)) {
					// unable to create? Stop with errors
					trigger_error('Could not select database');
					$this->connectError=true;
				}
			}
		}
		//$this->query("SET CHARACTER SET '$this->dbType'");
	}

	function CTFdb($dbase=OWASPCTFDB, $uid=OWASPCTFUID, $pwd=OWASPCTFPWD, $create=false, $type="UTF-8") {
		$this->db = mysql_connect("localhost", $uid, $pwd);
		if (!$this->db || !mysql_select_db($dbase, $this->db)) {
			if ($create === true) {
				$this->createDatabase($dbase, $uid, $pwd);
				if (!$this->db || !mysql_select_db($dbase, $this->db)) {
					throw new Exception('Database fail: ' . mysql_error($this->db));
				}
			} else {
				throw new Exception('Database fail: ' . mysql_error($this->db));
			}
		}
		$this->execute("SET CHARACTER SET '$type'");
	}
	/**
	 * Checks for MySQL errors
	 * @return boolean
	 * @access public
	 */
	function isError () {
		if ( $this->connectError )
		return true;
		$error=mysql_error ($this->dbConn);
		if ( empty ($error) )
		return false;
		else
		return true;
	}

	/**
	 * Returns an instance of MySQLResult to fetch rows with
	 * @param $sql string the database query to run
	 * @return MySQLResult
	 * @access public
	 */
	function query($sql) {
		$this->lastquery = $sql;
		
		if (!$queryResource=mysql_query($sql,$this->dbConn)) {
			trigger_error ('Query failed: '.mysql_error($this->dbConn).' SQL: '.$sql);
			return false;
		}
		return new MySQLResult($this,$queryResource);
	}

	var $lastquery;
	function testTables($sql) {
		if (!$queryResource=mysql_query($sql,$this->dbConn)) {
			@setupDB();
		}
	}

	function testTable($sql,$create) {
		if (!$queryResource=mysql_query($sql,$this->dbConn)) {
			$this->query($create);
			return true;
		}
		return false;
	}
}

/**
 * MySQLResult Data Fetching Class
 * @access public
 * @package SPLIB
 */
class MySQLResult {
	/**
	 * Instance of MySQL providing database connection
	 * @access private
	 * @var MySQL
	 */
	var $mysql;

	/**
	 * Query resource
	 * @access private
	 * @var resource
	 */
	var $query;

	/**
	 * MySQLResult constructor
	 * @param object mysql   (instance of MySQL class)
	 * @param resource query (MySQL query resource)
	 * @access public
	 */
	function MySQLResult(& $mysql,$query) {
		$this->mysql=& $mysql;
		$this->query=$query;
	}

	/**
	 * Fetches a row from the result
	 * @return array
	 * @access public
	 */
	function fetch () {
		if ( $row=mysql_fetch_array($this->query,MYSQL_ASSOC) ) {
			return $row;
		} else if ( $this->size() > 0 ) {
			mysql_data_seek($this->query,0);
			return false;
		} else {
			return false;
		}
	}

	/**
	 * Fetches all rows from the result
	 * @return array
	 * @access public
	 */
	function fetchAll() {
		$array = array();
		while($row = $this->fetch()) {
			$array[] = $row;
		}
		return $array;
	}

	/**
	 * Returns the number of rows selected
	 * @return int
	 * @access public
	 */
	function size () {
		return mysql_num_rows($this->query);
	}

	/**
	 * Returns the ID of the last row inserted
	 * @return int
	 * @access public
	 */
	function insertID () {
		return mysql_insert_id($this->mysql->dbConn);
	}

	/**
	 * Checks for MySQL errors
	 * @return boolean
	 * @access public
	 */
	function isError () {
		return $this->mysql->isError();
	}
}
?>