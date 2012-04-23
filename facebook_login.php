 <?php 

 require_once './phpservice/facebook/src/facebook.php';
 require_once('./phpservice/class/help.php');
 
   $app_id = "214122235315121";
   $app_secret = "ee611e5402e7062e53245b0a56ba26d8";
   $my_url = "http://www.weibospace.com/facebook_login.php";

   $code = $_REQUEST["code"];
   
   //create the final string to be posted using implode()
   $post_string = "grant_type=client_credentials&client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;
   $result = HELP::post('https://graph.facebook.com/oauth/access_token',$post_string);
   
   $accessToken = $result;
   
   echo $accessToken;

/*
$facebook = new Facebook(array(
  'appId'  => '214122235315121',
  'secret' => 'ee611e5402e7062e53245b0a56ba26d8',
));

// Get User ID
$userid = $facebook->getUser();

if ($userid) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		echo 'get user :' . $userid;
		echo 'token:' . $facebook->getAccessToken();
		$user = $facebook->api('/me');
		var_dump($user);
		
	} catch (FacebookApiException $e) {
//		error_log($e);
		echo $e;
		$userid = null;
	}
}
*/
   
   /*
   echo $data->access_token;
   echo $data->user->name;
   */
 ?>