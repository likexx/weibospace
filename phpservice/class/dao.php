<?php 

class DAO {
	const HOST = "localhost";
	const USERNAME = "myjoyspace";
	const PASSWORD = "fourtwofive";
	const DATABASE = "myjoyspace";
	
	public static function getConnection() {
		$con = mysql_connect(self::HOST, self::USERNAME, self::PASSWORD);
		mysql_select_db(self::DATABASE, $con);
		
		return $con;
	}
	
}

?>