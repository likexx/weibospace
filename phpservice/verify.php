<?php 
require_once(dirname(__FILE__) . '/class/qq.php');
require_once(dirname(__FILE__) . '/class/weibo.php');
require_once(dirname(__FILE__) . '/class/fb.php');
require_once(dirname(__FILE__) . '/class/renren.php');

if (!isset($_COOKIE['uid'])) {
	return;
}

$uid = $_COOKIE['uid'];

if (isset($_POST['type'])) {
	$type = $_POST['type'];
	
	$result = null;
	switch ($type) {
		case 'weibo':
			$result = Weibo::checkToken($uid);
			break;
		case 'qq':
			$result = QQ::checkToken($uid);
			break;
		case 'renren':
			$result = Renren::checkToken($uid);
			break;
		case 'facebook':
			$result = FB::checkToken($uid);
			break;
	}
}

echo json_encode($result);

?>