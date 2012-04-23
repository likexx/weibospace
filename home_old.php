<?php 

require_once('./phpservice/class/node.php');

$uid = 0;

if (isset($_COOKIE['uid'])) {
   $uid = $_COOKIE['uid'];
} 
//echo $uid;

$node = new Node();

$user = $node->dataPush(0, 'finduserbyid', array(
            'uid'=>$uid
          ));

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

<link rel="stylesheet" href="./main.css" type="text/css" />
<link rel="stylesheet" href="./css/ui-darkness/jquery-ui-1.8.16.custom.css" type="text/css" />
<script type="text/javascript" src="./js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="./js/yahoo-min.js"></script>
<script type="text/javascript" src="./js/json-min.js"></script>
<script type="text/javascript" src="./js/app.js"></script>
<script src="http://106.187.43.45:54321/socket.io/socket.io.js"></script>
<script type="text/javascript" src="./js/newsfeed.js"></script>

</head>
<body onload="APP.initHomePage();NewsFeed.init();">

<div class="main_div">

<div id="header">
    <div class="top_links">
      <ul>
        <span>爱玩休闲小游戏吗？期待一点点挑战吗?加入我们吧，看看你在全世界玩家中的排名</span></li>
      </ul>
    </div>
    <br/>
    <div style="position:relative; top:20px;left:50px; color:white;font-size:24px;font-weight:bold;">
    <table><tr><td width="600px" style="cursor:pointer" onclick="window.location.href='./index.html';">
    MyJoySpace.com
             游乐地带
    </td><td>
    <div style="font-size:20px;color:#FFF;padding:0px 0px 0px 0px;">
    Welcome, <span style="cursor:pointer;" onclick="APP.openNewWindow('<?php echo $user->profile;?>');"><?php echo $user->username;?></span> <span style="cursor:pointer;" onclick="APP.openNewWindow('<?php echo $user->profile;?>');"><img src="<?php echo $user->image;?>" width="60px" height="45px"/></span>
    </div>
	</td></tr></table>
    </div>

</div>
<br/>


<div style="font-size:20px; top:200px; height:800px;">
<hr/>
<div id="LOADING_DIV" style="margin:0 auto;color:white;font-weight:bold;font-size:30px;padding:50px 50px 50px 50px;">
<center>和服务器连接中，请稍候...</center>
</div>
<div id="MAIN_MENU"  style="height:100%;visibility:hidden;">
	<ul>
	<li><a href='#MAIN_MENU-1' onclick="NewsFeed.getNewsFeed();">最新动态</a></li>
	<li><a href='#MAIN_MENU-2' style='cursor:pointer;' onclick="APP.showTouch2Summary();">点点看</a></li>
    <li><a href='#MAIN_MENU-3' style='cursor:pointer;' onclick="APP.showSlidePuzzle();">拼图游戏(测试)</a></li>
    <li><a href='#MAIN_MENU-4' style='cursor:pointer;' onclick="APP.showRow5();">五子棋对战</a></li>
    <li><a href='#MAIN_MENU-5'>用户反馈和讨论</a></li>
	</ul>
	<div id="MAIN_MENU-1">
      <div id="REALTIME_GAME_SCORE" style="font-size:14px;">
      </div>
      <hr/>
	</div>
	<div id="MAIN_MENU-2">
    	<div id="touch2_GAME_CANVAS" style="margin:0 auto;width:800px;height:600px;font-size:14px;">
    	</div>
	</div>
	<div id="MAIN_MENU-3">
        <div id="slidepuzzle_GAME_CANVAS" style="margin:0 auto;width:800px;height:600px;font-size:12px;">
        </div>
	</div>
    <div id="MAIN_MENU-4">
        <div id="row5_GAME_CANVAS" style="margin:0 auto;width:800px;height:600px;font-size:12px;">
        </div>
    </div>
    <div id="MAIN_MENU-5">
   <iframe src="http://www.likexx.net/myjoyspace_cn" width="800" height="600"></iframe>
    </div>
</div>

    
</div>




</div>

</body>

</html>