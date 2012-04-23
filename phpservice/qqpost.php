<?php 

require_once('./class/qq.php');
require_once './facebook/src/facebook.php';
require_once('./class/renren.php');


$qq = new QQ();

if (!isset($_COOKIE['usertype']))    {
    return;
}

$usertype = $_COOKIE['usertype'];

if (!isset($_POST['title']) ||
    !isset($_POST['summary'])) {
        echo 'missing value';
        return;
    }

    
switch ($usertype) {


    case 'qq':
        $title = htmlentities(trim($_POST['title']),ENT_QUOTES, "UTF-8");
        $summary = htmlentities(trim($_POST['summary']),ENT_QUOTES, "UTF-8");
        
        $token = $_COOKIE['access_token'];
        $openid = $_COOKIE['uid'];
        
        $data = array(
            'access_token'=>$token,
            'openid'=>$openid,
            'title'=>$title,
            'url'=>'http://www.myjoyspace.com',
            'summary'=>$summary,
            'images'=>'http://www.myjoyspace.com/image/game_symbol.jpg',
            'nswb'=>'1'
        );
        
        
        $result = $qq->post($data);
        $result = json_decode($result);
        
        while ($result->ret == 3021) {
            $data['url'] = $data['url'] . '?time=' . rand();
            $result = $qq->post($data);
            $result = json_decode($result);
        }
        
        echo json_encode($result);
    break;
    case 'weibo':
        $title =  $_POST['title'] . '.欢迎加入休闲游戏联赛  http://www.myjoyspace.com?time=' . rand();
        $title = htmlentities(trim($title),ENT_QUOTES, "UTF-8");
        $title = urlencode($title);
        $data=array(
            'access_token'=>$_COOKIE['access_token'],
            'status'=>$title
        );
        $result = HELP::postData('https://api.weibo.com/2/statuses/update.json',$data);
        
        echo $result;
    break;
    
    case 'facebook':

        $title =  $_POST['title'] . '.欢迎加入休闲游戏联赛  http://www.myjoyspace.com?time=' . rand();
        $title = htmlentities(trim($title),ENT_QUOTES, "UTF-8");
    
        $app_id = "214122235315121";
        $app_secret = "ee611e5402e7062e53245b0a56ba26d8";

        $accessToken = $_COOKIE['access_token'];        

        $facebook = new Facebook(array(
          'appId'  => $app_id,
          'secret' => $app_secret,
        ));
        $facebook->setAccessToken($accessToken);
        $userid = $facebook->getUser();
        
        echo $userid;

        if ($userid) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                
                $ret_obj = $facebook->api('/me/feed', 'POST',
                                          array(
                                                 'link' => 'www.myjoyspace.com',
                                                 'message' => $title
                                         ));
                
            } catch (FacebookApiException $e) {
                echo $e;
                $userid = null;
            }
        }
    
    break;
    
    case 'renren':

        $title =  $_POST['title'] . '.欢迎加入休闲游戏联赛  http://www.myjoyspace.com?time=' . rand();
        $title = htmlentities(trim($title),ENT_QUOTES, "UTF-8");
        
        Renren::post($title, $_COOKIE['access_token']);
    
    break;

}
?>