<?php 
require_once( dirname(__FILE__) . '/../renren/RenrenRestApiService.class.php' );
require_once(dirname(__FILE__) . '/help.php');
require_once(dirname(__FILE__) . '/dao.php');


class Renren {

	const app_key = "4e0d6eb878da4afeaed6f8c4bc200e9f";
	const app_secret = "f80639394a974635b2f41cbd08cd70a9";
	
	
	public static function getUser($code) {
		$my_url = "http://www.weibospace.com/phpservice/registeruser.php?type=renren";
		
		$post_string = "grant_type=authorization_code&"
		. "client_id=" . self::app_key . "&redirect_uri=" . urlencode($my_url)
		. "&client_secret=" . self::app_secret . "&code=" . $code;
		
		$result = HELP::post('https://graph.renren.com/oauth/token',$post_string);
//		error_log('renren->getUser: ');
//		error_log($result);
		
		$data = json_decode($result);
		
//        $user = self::getCurrentUser($data->access_token);		
		return array(
		    'user'=>$data->user,
		    'token'=>$data->access_token
		);
	}
	
	public static function getCurrentUser($access_token) {
		$rr = new RenrenRestApiService;
		
		$params = array(
				'fields'=>'uid,name,sex,birthday,mainurl,hometown_location,tinyurl,headurl,mainurl',
				'access_token'=>$access_token);
		$res = $rr->rr_post_curl('users.getInfo', $params);
		
		if (count($res) > 0) {
			return $res[0];
		}
		
		return null;
	}
	
	public static function post($message, $access_token, $image=null) {
        
        $rr = new RenrenRestApiService;
        
        $params = array(
        		'status'=>$message,
        		'access_token'=>$access_token);
        $result = "{}";    
        if ($image != null) {
//        	$params['upload']= $image;
        	
        	$ch = curl_init();
        	$params = array(
        			'api_key' => self::app_key,
        			'method' => 'photos.upload',
        			'v' => '1.0',
        			'call_id' => time(),
        			'access_token'=>$access_token,
        			'format' => 'json',
        			'caption' => $message
        	);
        	//计算sig
        	ksort($params);
        	$sig = '';
        	foreach($params as $key=>$value) {
        			$sig .= "$key=$value";
        	}
        	$sig .= self::app_secret;
        	$params['sig'] = md5($sig);
        	
        	//计算sig时不要把要上传的文件包括在内。所以放到sig后面加入数组
        	$params['upload'] = '@/var/www/html/upload/test.jpg';
        	curl_setopt($ch, CURLOPT_URL, "http://api.renren.com/restserver.do");
        	curl_setopt($ch, CURLOPT_POST, 1);
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        	$result = curl_exec($ch);
        	
//            $result = $rr->rr_post_curl('photos.upload', $params);
        } else {
            $result = $rr->rr_post_curl('status.set', $params);
        }
        return json_encode($result);
	}
	
	public static function registerUser($id, $token, $userId) {
		return HELP::registerUser("renren", $id, $token, $userId);
	}
	
	public static function getUserInfo($uid) {
		return HELP::getUserInfo("renren", $uid);
	}
	
	public static function publish($uid, $content, $image=null) {
		$encodedContent = htmlentities(trim($content),ENT_QUOTES, "UTF-8");
		$info = self::getUserInfo($uid);
		return self::post($encodedContent, $info['token']);
		
//		return self::post($encodedContent, $info['token'], '@/home/likezhang/logo.png');
	}
	
	public static function getNewsfeed($uid) {
//		echo "get friends staus: " . $uid;
		$info = self::getUserInfo($uid);
		
		if ($info!=null) {
			$access_token = $info['token'];

			$rr = new RenrenRestApiService;
			
			$params = array (
					'access_token'=>$access_token,
					'type'=>'10,11,20,21,30,31,50,51,52'
					);
			$feeds = $rr->rr_post_curl('feed.get', $params);
			
			return json_encode($feeds);
			
			/*
			$params = array(
					'access_token'=>$access_token);
			$friends = $rr->rr_post_curl('friends.get', $params);
			
			if (count($friends)>0){
				
				$status_list=array();
				foreach ($friends as $friend_id) {
					$status = $rr->rr_post_curl('status.gets',array('uid'=>$friend_id,'access_token'=>$access_token));
					$status_list[] = $status;
//					var_dump($status);
				}
//				return $status_list;
				return json_encode($status_list);
		
			}
			*/
		}
		
		return "{}";
		
	}
	
	public static function checkToken($uid) {
	
		$info = self::getUserInfo($uid);
		
		if ($info!=null) {
			
			$user = self::getCurrentUser($info['token']);

			if ($user!=null){
	
				error_log("renren id " . $user->uid . " : " . $info['id']);
				if ($user->uid == $info['id']) {
					return array(
							'error_code' => 0
							);
				}
			}
		}
		
		return array(
				'error_code' => 1
		);
		
	}
	
}

?>