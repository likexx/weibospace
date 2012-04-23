<?php 
if (isset($_COOKIE['uid'])) {
	//setcookie("uid", "", time() - 3600);
	header("Location: /home.php");
}

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta property="qc:admins" content="4356001777675127301356375" />
<meta property="wb:webmaster" content="703f2183e8c90076" />

<link rel="stylesheet" href="./main.css" type="text/css" />
<link rel="stylesheet" href="./css/ui-darkness/jquery-ui-1.8.16.custom.css" type="text/css" />
<script type="text/javascript" src="./js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="./js/yahoo-min.js"></script>
<script type="text/javascript" src="./js/json-min.js"></script>
<script type="text/javascript" src="./js/app.js"></script>


</head>
<body onload="APP.initIndexPage();">

          
<div class="main_div">

<div id="header">
    <div class="top_links">
      <ul>
        <span>所有微博，一次发布</span></li>
      </ul>
    </div>
    <br/>
    <div style="position:relative; top:20px;left:50px; color:white;font-size:24px;font-weight:bold;">
    WeiboSpace.com
             微博空间
    </div>
</div>

<br/>
<br/>
<br/>
<div id="MAIN_MENU" class="main_menu">
<hr/>

      <div style="float:left;width:40%;border-radius:9px;background-color:#CCC;">
        <ul>
          <li style="color:#555;padding:10px 10px 10px 10px;">
          无须注册，快速登录<br/><br/>
          </li>
          <li>
          <img class='login_button' src='./image/qq_login.png' onclick="APP.qqLogin();"></img>
          <br/>
          <img class='login_button' src='./image/weibo_login.png' onclick="APP.weiboLogin();"></img>
          <br/>
          <img class='login_button' src='./image/renren_login.png' style="cursor:pointer;" onclick="APP.renrenLogin();"></img>
          <br/>
          <img class='login_button' src='./image/facebook_login.png' style="cursor:pointer;" onclick="APP.facebookLogin();"></img>
          </li>
        </ul>
      </div>

    
</div>

</div>

</body>

</html>