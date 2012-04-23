<?php 

class HELP {

	public static function updateNext() {
	if (isset($_GET['next'])) {
	    
		$steps = explode(',', $_GET['next']);
		$stepCount = count($steps);
	    
		if ( $stepCount > 0) {
	
			$next = $steps[0];
			$rest = "";
			
			for($i = 1;$i<$stepCount;++$i) {
				
				if (strlen($steps[$i])==0) {
					continue;
				}
				
				$rest = $rest . $steps[$i] . ',';
			}

            if (strcmp($next, 'qq')==0) {
			echo '<script>window.location.href= "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=100239144&' .
			'redirect_uri=' . urlencode("http://www.weibospace.com/phpservice/update_qq.php?next=". $rest) .
			'&scope=get_user_info,add_share";</script>';
	
			} else if (strcmp($next,'renren')==0) {
			echo '<script>window.location.href="https://graph.renren.com/oauth/authorize?client_id=175249&response_type=code&redirect_uri=' .
			urlencode("http://www.weibospace.com/phpservice/update_renren.php?next=" . $rest) . '&display=page&scope=status_update,publish_share";</script>';
	
			} else if (strcmp($next,'fb')==0) {
			echo '<script>window.location.href="https://www.facebook.com/dialog/oauth?client_id=214122235315121&redirect_uri=' .
			urlencode("http://www.weibospace.com/phpservice/update_facebook.php?next=" . $rest) .
			'&scope=email,read_stream,status_update,publish_stream,offline_access";</script>';
	
			}
	
		}
	
     }
    
   }
	
	public static function post($url, $post_string) {
		//create cURL connection
		$curl_connection = curl_init($url);
		//set options
		curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($curl_connection, CURLOPT_USERAGENT,
					  'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');
		curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
		//set data to be posted
		curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
		//perform our request
		$result = curl_exec($curl_connection);
		//show information regarding the request
		/*
		 print_r(curl_getinfo($curl_connection));
		echo curl_errno($curl_connection) . '-' .
		*/
		curl_error($curl_connection);
		//close the connection
		curl_close($curl_connection);
	
		return $result;
	
	}
	
	
	public static function postData($url, $postData) {
		//traverse array and prepare data for posting (key1=value1)
		foreach ( $postData as $key => $value) {
			$post_items[] = $key . '=' . $value;
		}
		//create the final string to be posted using implode()
		$post_string = implode ('&', $post_items);
		
		return self::post($url, $post_string);
		
	}
	
	public static function get($url, $get_string) {
		//create cURL connection
		$curl_connection = curl_init($url . '?' . $get_string);
		//set options
		curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl_connection, CURLOPT_USERAGENT,
						  'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');
		curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
		//set data to be posted
		//perform our request
		$result = curl_exec($curl_connection);
		//show information regarding the request
		/*
		 print_r(curl_getinfo($curl_connection));
		echo curl_errno($curl_connection) . '-' .
		*/
		curl_error($curl_connection);
		//close the connection
		curl_close($curl_connection);
	
		return $result;
	
	}

	public static function getData($url, $data) {
		foreach ( $data as $key => $value) {
			$items[] = $key . '=' . $value;
		}
		//create the final string to be posted using implode()
		$paras = implode ('&', $items);
	
		return self::get($url, $paras);
	}
	
	
	public static function registerUser($type, $id, $token, $userId) {

		$userDatabase = $type . "_user";
		
		$con = DAO::getConnection();
		$sql = "SELECT * FROM " . $userDatabase . " WHERE extid='$id'";
	
		$rows = mysql_query($sql, $con);
	
		$rowCount = mysql_num_rows($rows);
		$uid = 0;

		if ($rowCount < 1) {
			$username = $type . "_" . $id;
	
			if ($userId == 0) {
				// add to global user lookup table
				$sql = "INSERT INTO user (username) VALUES ('$username')";
				mysql_query($sql, $con);
	
				$rows = mysql_query("SELECT id from user where username='$username'", $con);
				$row = mysql_fetch_assoc($rows);
				$uid = $row['id'];
			} else {
				$rows = mysql_query("SELECT id from user where id='$userId'", $con);
				$rowCount = mysql_num_rows($rows);
				if ($rowCount>0) {
					$uid = $userId;
				}
			}
			// add to qq_user
			$sql = "INSERT INTO " . $userDatabase . " (userid, extid, token) VALUES ('$uid', '$id', '$token')";
			mysql_query($sql, $con);
		} else {
			$row = mysql_fetch_assoc($rows);
			$uid=$row['userid'];
			
			// update token
			$sql = "UPDATE " . $userDatabase . " SET token='$token' WHERE userid='$uid'";
			mysql_query($sql, $con);
		}
	
		mysql_close($con);
		return $uid;
	}
	
	public static function getUserInfo($type, $userid) {
		$con = DAO::getConnection();
		$database = $type . "_user";
		$sql = "SELECT * FROM " . $database . " WHERE userid='$userid'";
		$rows = mysql_query($sql, $con);
		
		$row = mysql_fetch_assoc($rows);
		
		$result = null;
		if ($row) {
		
			$result = array(
					"token"=> $row['token'],
					"id"=>$row['extid']
			);
		}
		
		mysql_close($con);
		
		return $result;
		
	}
	
}

?>