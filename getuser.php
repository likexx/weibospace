<?php 

require_once('./phpservice/class/node.php');

if (!isset($_COOKIE['uid'])) {
	echo 'no id';
	return;
}

$uid = isset($_POST['uid']) ? $_POST['uid'] : $_COOKIE['uid'];

$node = new Node();

$user = $node->dataPush(0, 'finduserbyid', array(
            'uid'=>$uid
          ));

echo json_encode($user);

?>