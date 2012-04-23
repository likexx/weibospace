<?php 
require_once(dirname(__FILE__) . '/class/qq.php');
require_once(dirname(__FILE__) . '/class/weibo.php');
require_once(dirname(__FILE__) . '/class/fb.php');
require_once(dirname(__FILE__) . '/class/renren.php');

function getSource() {
	$source = array();
	switch($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			$source = $_POST;
			break;
		case "GET":
			$source = $_SESSION;
			break;
		default:
			break;
	}
	return $source;
}

function getSetting($key) {
	$source = getSource();
	return isset($source[$key]) ? $source[$key] : false;
}

function removeSetting($type){
	$source = getSource();
	
	if (isset($source[$type])) {
		$source[$type] = false;
	}
}


function tryPublish($uid, $content) {
	$result = array();

	if (getSetting('weibo')=='true') {
		$weiboResult = Weibo::publish($uid, $content);
		$result['weibo'] = $weiboResult;
		removeSetting('weibo');
	}
	
	
	if (getSetting('qq') == 'true') {
		$qqResult = QQ::publish($uid, $content);
		$result['qq'] = $qqResult;
		removeSetting('qq');
	};
	
	if (getSetting('renren')== 'true') {
		$renrenResult = Renren::publish($uid, $content);
		$result['renren'] = $renrenResult;
		removeSetting('renren');
	}
	
	if (getSetting('facebook')== 'true') {
		$fbResult = FB::publish($uid, $content);
		$result['facebook'] = $fbResult;
		removeSetting('facebook');
	}
	
	return $result;
}


if (!isset($_COOKIE['uid'])) {
	return;
}

$uid = $_COOKIE['uid'];
session_start();

$content = getSetting('content');
$result = tryPublish($uid, $content);

echo json_encode($result);

?>