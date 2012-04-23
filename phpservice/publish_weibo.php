<?php 
require_once('./class/userWeibo.php');
session_start();

if (!isset($_POST['content'])){
	echo 'error';
} else {
	$content = $_POST['content'];
	$_SESSION['content'] = $content;
	$uid = $_COOKIE['uid'];
	$user = new UserWeibo($uid);
	
	$ret = $user->publish($content);
	
	echo $ret;
	return;
}

?>