<?php 

require './facebook/src/facebook.php';

        $app_id = "214122235315121";
        $app_secret = "ee611e5402e7062e53245b0a56ba26d8";

        $accessToken = $_COOKIE['access_token'];        

        $facebook = new Facebook(array(
          'appId'  => $app_id,
          'secret' => $app_secret,
        ));
        $facebook->setAccessToken($accessToken);
        $userid = $facebook->getUser();
        

if ($userid) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		echo 'get user :' . $userid;
//		$user_profile = $facebook->api('/me');
		
		$ret_obj = $facebook->api('/me/feed', 'POST',
								  array(
		                                 'link' => 'www.myjoyspace.com',
		                                 'message' => '测试测试3\r\n日，怎么换行？？<br><br/>[br] 一起来耍小游戏(测试用，请忽略)'
		                         ));
		echo 'post done';
		var_dump($ret_obj);
		
	} catch (FacebookApiException $e) {
//		error_log($e);
		echo $e;
		$userid = null;
	}
}

?>