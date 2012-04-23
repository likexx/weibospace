<?php 
require_once(dirname(__FILE__) . '/class/qq.php');
require_once(dirname(__FILE__) . '/class/weibo.php');
require_once(dirname(__FILE__) . '/class/fb.php');
require_once(dirname(__FILE__) . '/class/renren.php');


if (!isset($_COOKIE['uid'])) {
	return;
}
$uid = $_COOKIE['uid'];

if (!isset($_REQUEST['type'])) {
	return;
}

$type = $_REQUEST['type'];

$result = "";

switch ($type) {
	case 'weibo':
		$result = Weibo::getNewsFeed($uid);
		break;
	case 'renren':
		$result = Renren::getNewsfeed($uid);
		break;
	case 'facebook':
		$result = FB::getNewsfeed($uid);
		break;
}
//$result = Weibo::getNewsFeed($uid);

echo $result;
?>
