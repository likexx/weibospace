<?php
require_once(dirname(__FILE__) . '/dao.php');
require_once(dirname(__FILE__) . '/help.php');

class Weibo {

    const APP_KEY = '739932394';
    const APP_SECRET = '4d71deb35bff29683ad896c3d4db516e';


    public static function getAuthInfo($code, $url) {
        $post_string = "grant_type=authorization_code&client_id=" . self::APP_KEY . "&redirect_uri=" . urlencode($url)
        . "&client_secret=" . self::APP_SECRET . "&code=" . $code;
        $result = HELP::post('https://api.weibo.com/oauth2/access_token',$post_string);
        return $result;
    }

    public static function publish($uid, $content) {
        $encodedContent = htmlentities(trim($content),ENT_QUOTES, "UTF-8");
        $info = self::getUserInfo($uid);

        $value=array(
                "access_token"=>$info['token'],
                "status"=>$encodedContent
        );
        $result = HELP::postData('https://api.weibo.com/2/statuses/update.json',$value);
/*
        $ch = curl_init();
        $params = array(
        		'access_token'=>$info['token'],
        		'status' => $content,
        );
        $params['pic'] = '@/var/www/html/upload/test.jpg';
        curl_setopt($ch, CURLOPT_URL, "https://api.weibo.com/2/statuses/upload.json");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
*/        
        return $result;
    }
    
    public static function getNewsFeed($uid) {
    	$info = self::getUserInfo($uid);
    	$result = HELP::getData('https://api.weibo.com/2/statuses/home_timeline.json',
    			                 array(
    			                  'access_token'=>$info['token']
    			                 ));
    	
    	return $result;
    	
    }

    public static function getUserInfo($uid) {
        return HELP::getUserInfo("weibo", $uid);
    }


    public static function registerUser($id, $token, $userId) {

        return HELP::registerUser("weibo", $id, $token, $userId);

    }

    public static function checkToken($uid) {

        $info = self::getUserInfo($uid);
        
        if ($info != null) {
	        $get_string = "access_token=" . $info['token'] . "&uid=" . $info['id'];
	
	        $result = HELP::get('https://api.weibo.com/2/users/show.json', $get_string);
	        try {
	            $user = json_decode($result);
	            /*
	             $username = $user->name;
	            $image = $user->profile_image_url;
	            $gender = $user->gender=="m" ? 'm' : 'f';
	            */
	            $extId = $user->id;
	            /*
	             $profile = "http://www.weibo.com/" . $user->profile_url;
	            */
	            if ($extId == $info['id']) {
	                return array(
	                        'error_code' => 0
	                );
	            }
	        } catch (Exception $e) {
	                
	        }
        }

        return array(
                'error_code' => 1
        );
    }
    
    public static function getComments($uid, $weiboId) {
    	//https://api.weibo.com/2/comments/show.json
    	$info = self::getUserInfo($uid);
    	$result = HELP::getData('https://api.weibo.com/2/comments/show.json',
    			array(
    					'access_token'=>$info['token'],
    					'id'=>$weiboId
    			));
    	
    	return $result;
    	
    }

}
?>
