<?php

class Database {

	private static $db;
	private $connection;

	private function __construct() {
        // DB DETAILS REMOVED FROM GIT REPO
        $this->connection = new MySQLi("###", "###", "###", "###");
	}

	function __destruct() {
		$this->connection->close();
	}

	public static function getConnection() {
		if (self::$db == null) {
			self::$db = new Database();
		}
		return self::$db->connection;
	}
}
