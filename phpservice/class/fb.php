<?php
require_once( dirname(__FILE__) . '/../facebook/src/facebook.php' );
require_once(dirname(__FILE__) . '/help.php');
require_once(dirname(__FILE__) . '/dao.php');

class FB {

	const APP_ID = "214122235315121";
	const APP_SECRET = "ee611e5402e7062e53245b0a56ba26d8";
	
	
	public static function registerUser($id, $token, $userId) {
	
		return HELP::registerUser("fb", $id, $token, $userId);
	
	}

	public static function getUserInfo($uid) {
		return HELP::getUserInfo("fb", $uid);
	}
	
	public static function publish($uid, $content) {
		
		$error = array();
		
		$validContent = htmlentities(trim($content),ENT_QUOTES, "UTF-8");
		
		$info = self::getUserInfo($uid);

		$facebook = new Facebook(array(
				'appId'  => self::APP_ID,
				'secret' => self::APP_SECRET,
		));
		$facebook->setAccessToken($info['token']);

		$userid = $facebook->getUser();
		
		if ($userid) {
			try {
				// Proceed knowing you have a logged in user who's authenticated.
		
				$ret_obj = $facebook->api('/me/feed', 'POST',
						array(
								'link' => 'www.weibospace.com',
								'message' => $validContent
						));
				
				return json_encode($ret_obj);
		
			} catch (FacebookApiException $e) {
				$userid = null;
			}
		}
		
		return $error;
	}
	
	public static function checkToken($uid) {
	
		$info = self::getUserInfo($uid);
		error_log("extid: " . $info['id']);
		
		if ($info != null) {
	
			$facebook = new Facebook(array(
					'appId'  => self::APP_ID,
					'secret' => self::APP_SECRET,
			));
			$facebook->setAccessToken($info['token']);
	
			$userid = $facebook->getUser();
	
			error_log($userid . " : " . $info['id']);
			
			if ($userid == $info['id']) {
				return array(
						'error_code' => 0
						);
			}
		}
		return array(
					'error_code' => 1
				);
	}
	
	public static function getNewsfeed($uid) {
		$error = array();
		
		$info = self::getUserInfo($uid);
		
		$facebook = new Facebook(array(
				'appId'  => self::APP_ID,
				'secret' => self::APP_SECRET,
		));
		$facebook->setAccessToken($info['token']);
		
		$userid = $facebook->getUser();
		
		if ($userid) {
			try {
				// Proceed knowing you have a logged in user who's authenticated.
		
				$ret_obj = $facebook->api('/me/home', 'GET',
						array());
		
				return json_encode($ret_obj);
		
			} catch (FacebookApiException $e) {
				$userid = null;
			}
		}
		
		return $error;		
	}
	
}

?>