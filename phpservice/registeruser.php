<?php 

require_once(dirname(__FILE__) . '/class/node.php');
require_once(dirname(__FILE__) . '/class/qq.php');
require_once(dirname(__FILE__) . '/class/weibo.php');
require_once(dirname(__FILE__) . '/class/renren.php');
require_once(dirname(__FILE__) . '/class/fb.php');
require_once(dirname(__FILE__) . '/class/help.php');
require_once(dirname(__FILE__) . '/facebook/src/facebook.php');

$DATA = null;

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET': $DATA = &$_GET; break;
	case 'POST': $DATA = &$_POST; break;
	break;
}

$username = isset($DATA['username']) ? $DATA['username'] : null;
$password = isset($DATA['password']) ? $DATA['password'] : null;
$extId = isset($DATA['extid']) ? $DATA['extid'] : null;
$type = isset($DATA['type']) ? $DATA['type'] : null;
$image = isset($DATA['image']) ? $DATA['image'] : null;
$gender = isset($DATA['gender']) ? $DATA['gender'] : null;
$profile = "";

$uid = 0;

if(isset($_COOKIE['uid'])) {
	$uid = $_COOKIE['uid'];
}

switch ($type) {
	case 'qq':

		$code = $_REQUEST["code"];
		
        $credential = QQ::getQQCredential($code);

        $value = QQ::getUser(array(
        		        'access_token'=>$credential['token'],
        		        'appid'=> QQ::CLIENT_ID,
        		        'openid'=>$credential['openid']
        ));
        
        $user = json_decode($value);
        /*
        $username = $user->nickname;
        $image = $user->figureurl_1;
        $gender = $user->gender=="男" ? 'm' : 'f';
        */
        
        $extId = $credential['openid'];
        
        $uid = QQ::registerUser($extId, $credential['token'], $uid);
        
        break;
	case "weibo":
		$app_key = "387605663";
		$app_secret = "c64a8c0e290f65a5a992c86d5c7e0bf7";

		$code = $_REQUEST["code"];

		$result = Weibo::getAuthInfo($code, "http://www.weibospace.com/phpservice/registeruser.php");

		$data = json_decode($result);

		$uid = Weibo::registerUser($data->uid, $data->access_token, $uid);

        /*
		$get_string = "access_token=" . $data->access_token . "&uid=" . $data->uid;
		
		$result = HELP::get('https://api.weibo.com/2/users/show.json', $get_string);
		
		$user = json_decode($result);
		
		$username = $user->name;
        $image = $user->profile_image_url;
        $gender = $user->gender=="m" ? 'm' : 'f';
        $extId = $user->id;
        $profile = "http://www.weibo.com/" . $user->profile_url;

        setcookie('access_token', $data->access_token, time()+3600*24*30);
		*/
		
		break;
	case 'renren':
		
		$data = Renren::getUser($_REQUEST["code"]);
		$user = $data['user'];
		$username = $user->name;
		/*
        $image = $user['headurl'];
        $gender = $user['sex'] == '1' ? 'm' : 'f';
        $extId = $user['uid'];
        $profile = "http://www.renren.com/profile.do?id=" . $extId;
        */
		$uid = Renren::registerUser($user->id, $data['token'], $uid);
		
//		echo $username;
		
//		$data = Renren::getCurrentUser($data['token']);
//		var_dump($data);
		
//		Renren::getFriendsStatus($uid);
		
//		return;

//        setcookie('access_token', $data['token'], time()+3600*24*30);
				
		break;
	case "facebook":
		
		$app_id = "214122235315121";
		$app_secret = "ee611e5402e7062e53245b0a56ba26d8";
		$my_url = "http://www.weibospace.com/phpservice/registeruser.php?type=facebook";

		
		$code = $_REQUEST["code"];
		
		//create the final string to be posted using implode()
		$post_string = "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
		. "&client_secret=" . $app_secret . "&code=" . $code;
		$result = HELP::get('https://graph.facebook.com/oauth/access_token',$post_string);
		$accessToken = substr($result, strpos($result, '=')+1);

		$facebook = new Facebook(array(
		  'appId'  => $app_id,
		  'secret' => $app_secret,
		));
        $facebook->setAccessToken($accessToken);
		$userid = $facebook->getUser();
		/*
		if ($userid) {
			$user = $facebook->api('/me');
			
			$username = $user['name'];
			
			$image = 'http://graph.facebook.com/' . $userid . '/picture';
   	        $gender = $user['gender']=="male" ? 'm' : 'f';
		    $extId = $userid;
		    $profile = "https://www.facebook.com/profile.php?id=" . $extId;
		} else {
			$dialog_url= "https://www.facebook.com/dialog/oauth?"
        				. "client_id=" . $app_id 
        				. "&redirect_uri=" . urlencode($my_url);
	      echo("<script> top.location.href='" . $dialog_url 
    		  . "'</script>");
		}
		*/
		
		$uid = FB::registerUser($userid, $accessToken, $uid);

		break;
}
/*
$node = new Node();

$result = $node->dataPush(0, 'register', array(
            'username'=>$username, 
            'password'=>$password,
	        'extId'=>$extId,
	        'type'=>$type,
	        'image'=>$image,
	        'gender'=>$gender,
	        'profile' => $profile
    ));
*/
//var_dump($result);$result->_id

setcookie('uid', $uid, time()+3600*24*30, '/');

//setcookie('uid', $result->_id, time()+3600*24*30, '/');

echo "<script>window.location.href='/home.php';</script>";

?>