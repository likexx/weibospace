<?php

require_once('./class/node.php');

if (!isset($_POST['gamename']) ||
    !isset($_POST['action'])) {
        echo 'no data';
        return;
    }

$gamename = $_POST['gamename'];
$action = $_POST['action'];

$node = new Node();

$result = $node->dataPush(0, $action, array(
            'gamename'=>$gamename,
          ));

echo json_encode($result);

?>