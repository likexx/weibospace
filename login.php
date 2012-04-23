<?php

if (!isset($_POST['username']) || !isset($_POST['password'])) {
	return;
}

$username = $_POST['username'];
$password = $_POST['password'];


$username = htmlentities(trim($username),ENT_QUOTES, "UTF-8");

if(strlen($username)>20) {
	echo "0";
	return;
}
if(strlen($password)>20) {
	echo "0";
	return;
}

// no need to validate password if they are encrypted
$passwordHash = md5($password);

$con = mysql_connect("localhost","myjoyspace","fourtwofive");
mysql_select_db("myjoyspace", $con);

if (!$con) {
 echo "0";
 return;
}

$sql = "select * from user where username='$username'";

$rows = mysql_query($sql, $con);

$rowCount = mysql_num_rows($rows);
$uid = 0;

if ($rowCount > 0) {
    $row = mysql_fetch_assoc($rows);
    if ($passwordHash == $row['passowrd']) {
    	$uid = $row['id'];
    }
} else {
	$sql="INSERT INTO user (username, password) VALUES ('$username','$passwordHash')";
	
	mysql_query($sql,$con);
	
	$rows = mysql_query("SELECT id from user where username='$username'", $con);
	
	$row = mysql_fetch_assoc($rows);
	$uid = $row['id'];
}

mysql_close($con);

setcookie('uid', $uid, time()+3600*24*30, '/');

echo $uid;

?>
