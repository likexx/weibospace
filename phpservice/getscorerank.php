<?php

require_once('./class/node.php');

$gamename = 'touchnumber';
$score=40;

$gamename = $_POST['gamename'];
$score=$_POST['score'];


$node = new Node();

$rank = $node->dataPush(0, 'SCORE_RANK', array(
            'gamename'=>$gamename,
            'score'=>$score
          ));

echo $rank->rank;

?>