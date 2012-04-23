<?php
require_once(dirname(__FILE__) . '/help.php');
require_once(dirname(__FILE__) . '/dao.php');

class QQ {
	
	const CLIENT_ID = '100260402';
	const CLIENT_SECRET = 'dddf5b60b4cee03a5aab5f39fce6bc3e';
	
	
	public static function getQQCredential($code) {
		$my_url = "http://www.weibospace.com/phpservice/registeruser.php?type=qq";
		
		//create the final string to be posted using implode()
		$post_string = "grant_type=authorization_code&"
		. "client_id=" . self::CLIENT_ID . "&redirect_uri=" . urlencode($my_url)
		. "&client_secret=" . self::CLIENT_SECRET . "&code=" . $code;
		
		$result = HELP::get('https://graph.qq.com/oauth2.0/token',$post_string);
		///		$data = json_decode($result);
		$start = strpos($result, "=");
		$end = strrpos($result,"&");
		$token = substr($result, $start+1, $end - $start - 1);
		
		$result = HELP::post("https://graph.qq.com/oauth2.0/me", "access_token=" . $token);
		$start = strpos($result,'openid":"') + 9;
		$end=strrpos($result,'"}');
		$openid = substr($result, $start, $end - $start);
		
		return array(
		    'token'=>$token,
		    'openid'=>$openid
		);
	}
	
	public static function getUser($params) {
		$get_string = "access_token=" . $params['access_token'] . "&oauth_consumer_key=" . $params['appid'] . "&openid=" . $params['openid'];
		
		return HELP::get('https://graph.qq.com/user/get_user_info', $get_string);
	}
	
	public static function post($postData) {
        $postData['oauth_consumer_key'] = self::CLIENT_ID;
//		return HELP::postData('https://graph.qq.com/share/add_share', $postData);
        return HELP::postData('https://graph.qq.com/t/add_t', $postData);
        
	}
	
	public static function publish($userid, $content) {
		$con = DAO::getConnection();
		
		$sql = "SELECT * FROM qq_user WHERE userid='$userid'";
		
		$rows = mysql_query($sql, $con);
		
		$rowCount = mysql_num_rows($rows);
		
		if ($rowCount < 1) {
			return;
		}
		
		$row = mysql_fetch_assoc($rows);
		$openid = $row['extid'];
		$token = $row['token'];
		/*
		$data = array(
				'access_token'=>$token,
				'openid'=>$openid,
				'title'=>$content,
				'url'=>'http://www.myjoyspace.com',
				'summary'=>$content,
				'nswb'=>'1'
		);
		*/
		$data = array(
				'access_token'=>$token,
				'oauth_consumer_key'=>self::CLIENT_SECRET,
				'openid'=>$openid,
				'format'=>'json',
				'content'=>$content,
				'syncflag'=>1
				);
		
		$result = self::post($data);
		
		return $result;
		
	}
	
	public static function getUserInfo($uid) {
		return HELP::getUserInfo("qq", $uid);

	}
	
	public static function registerUser($id, $token, $userId) {
		return HELP::registerUser("qq", $id, $token, $userId);
	}
	
	public static function checkToken($uid) {
	
		$info = self::getUserInfo($uid);
		
		if ($info != null) {
			$result = HELP::post("https://graph.qq.com/oauth2.0/me", "access_token=" . $info['token']);
			if (strpos($result, 'openid":"') > 0) {
				return array(
						'error_code' => 0
						);
			}
		}

		return array(
				'error_code' => 1
		);
	}
	
	
}
?>
