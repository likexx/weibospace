<?php 
require_once(dirname(__FILE__) . '/class/qq.php');
require_once(dirname(__FILE__) . '/class/weibo.php');
require_once(dirname(__FILE__) . '/class/fb.php');
require_once(dirname(__FILE__) . '/class/renren.php');


if (!isset($_COOKIE['uid'])) {
	return;
}
$uid = $_COOKIE['uid'];

if (!isset($_REQUEST['type']) || !isset($_REQUEST['id'])) {
	return;
}

$type = $_REQUEST['type'];
$id = $_REQUEST['id'];

$result = "";

switch ($type) {
	case 'weibo':
		$result = Weibo::getComments($uid, $id);
		break;
}
//$result = Weibo::getNewsFeed($uid);

echo $result;
?>
