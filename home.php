<?php 
require_once(dirname(__FILE__) . '/phpservice/class/qq.php');
require_once(dirname(__FILE__) . '/phpservice/class/weibo.php');
require_once(dirname(__FILE__) . '/phpservice/class/renren.php');
require_once(dirname(__FILE__) . '/phpservice/class/fb.php');


$uid = 0;

if (isset($_COOKIE['uid'])) {
   $uid = $_COOKIE['uid'];
} 
if ($uid == 0) {
	header("Location: http://www.weibospace.com/logout.php");
}

$weiboUser = Weibo::getUserInfo($uid);
$qqUser = QQ::getUserInfo($uid);
$renrenUser = Renren::getUserInfo($uid);
$fbUser = FB::getUserInfo($uid);

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
<script type="text/javascript" src="./js/ajaxfileupload.js"></script>
<script>
function publish() {
    var weibo_checked = $("#weibo_cb").prop("checked");
    var qq_checked = $("#qq_cb").prop("checked");
    var renren_checked = $("#renren_cb").prop("checked");
    var fb_checked = $("#facebook_cb").prop("checked");

    var content = $('#publish_content').val();
    if (content.length >140 || content.length<1) {
        return;
    }
    
    var content = {
		      content: content,
		      qq: qq_checked,
		      weibo: weibo_checked,
		      renren: renren_checked,
		      facebook: fb_checked
	};

	$.post('./phpservice/publish.php',
		   content,
		   function(data) {
		   var result = YAHOO.lang.JSON.parse(data);
		   if (result.next_url!= undefined && result.next_url.length >=1) {
			   window.location.href=result.next_url;
		   }
		   $('#publish_content').val('');
	});
	
}

function updateContentRest() {
    var content = $('#publish_content').val();
    var rest = 140 - content.length -1;
    $('#content_rest').html('你还可以输入' + rest + '字');
}

function processWeiboComments(id, data) {
    var result = YAHOO.lang.JSON.parse(data);
    var content="";
    for (var i in result.comments) {
        var comment = result.comments[i];
        content += "<img src='" + comment.user.profile_image_url + "'/>" + comment.user.screen_name + ": " + comment.text;
        content+="<br/>";
    }

    $('#weibo_comment_'+id).html(content);
}

function getComments(type, id) {
    $.post('./phpservice/get_comments.php',
            {
               type: type,
               id: id
            },
            function(data){
              switch(type) {
              case 'weibo':
                  processWeiboComments(id, data);
                  break;
              }
    });
}

function processWeiboNewsfeed(data) {
    var result = YAHOO.lang.JSON.parse(data);
    var content = "";
    for(var i in result.statuses) {
        var status = result.statuses[i];
        var user = status.user;
        content+= "<img src='" + user.profile_image_url + "'/>" + user.screen_name + ": " + status.text + "<br/>";
        content += "<span style='cursor:pointer;color:blue;' onclick='getComments(\"weibo\",\"" + status.id + "\");'>显示评论(" + status.comments_count + ")</span>";
        content += "<div id='weibo_comment_" + status.id + "' style='position:relative;left:30px;'></div>";
        content += "<hr>";
    }
    
    $('#weibo_newsfeed').html(content);
}

function processRenrenNewsfeed(data) {
    var result = YAHOO.lang.JSON.parse(data);
    var content = "";
    for (var i in result) {
         var feed = result[i];
         content+= feed.name + ": " + feed.message + ", " + feed.description + "<br/><hr>";
        
    }

    $('#renren_newsfeed').html(content);
}

function processFacebookNewsfeed(data) {
    var result = YAHOO.lang.JSON.parse(data);
    var content = "";
    for (var i in result.data) {
         var feed = result.data[i];
         content+=feed.from.name + ": " + feed.message + "<br/><hr>";
    }

    $('#facebook_newsfeed').html(content);
}

function getNewsfeed(type) {
    $.post('./phpservice/newsfeed.php?type=' + type,{},
            function(data){
              switch(type) {
              case 'weibo':
                  processWeiboNewsfeed(data);
                  break;
              case 'renren':
                  processRenrenNewsfeed(data);
                  break;
              case 'facebook':
                  processFacebookNewsfeed(data);
                  break;
              }
    });

}

function verifyStatus(type) {
    $.post('./phpservice/verify.php',
			   {type:type},
			   function(data) {
			   var result = YAHOO.lang.JSON.parse(data);
			   if (result.error_code == 0) {
				   $('#' + type + '_verify_status').html('已绑定');
				   $('#' + type + '_cb').css('visibility', 'visible');
				    getNewsfeed(type);
			   } else {
				   var link ="";
				   switch(type) {
				   case 'qq':
					   link = "APP.qqLogin();";
					   break;
				   case 'weibo':
					   link = "APP.weiboLogin();";
					   break;
				   case 'renren':
					   link = "APP.renrenLogin();";
					   break;
				   case 'facebook':
					   link = "APP.facebookLogin();";
					   break;
				   }
				   $('#' + type + '_verify_status').html('<span style="cursor:pointer;color:blue;" onclick="' + link + '">请重新绑定</span>');
			   }
		});

}

function ajaxFileUpload()
{
    /*
	$("#loading")
	.ajaxStart(function(){
//		$(this).show();
	})
	.ajaxComplete(function(){
//		$(this).hide();
	});
*/
	$.ajaxFileUpload
	(
		{
			url:'/phpservice/doajaxfileupload.php',
			secureuri:false,
			fileElementId:'fileToUpload',
			dataType: 'json',
			data:{name:'logan', id:'id'},
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}else
					{
						alert(data.msg);
					}
				}
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		}
	)
	
	return false;

}


$(document).ready(function () {
    verifyStatus('weibo');
    verifyStatus('qq');
    verifyStatus('renren');
    verifyStatus('facebook');

});
</script>

</head>

<body>

      <div style="width:40%;border-radius:9px;background-color:#CCC;">
      <a href='/logout.php'>退出</a><hr>
       新浪微薄: <span id='weibo_verify_status'>验证中。。。</span>
       <br/>
  QQ空间:<span id='qq_verify_status'>验证中。。。</span>
       	<br/>
       人人校内:<span id='renren_verify_status'>验证中。。。</span>
       	<br/>
  facebook: <span id='facebook_verify_status'>验证中。。。</span>
       	<br/>      
      
<hr>      
      
      	<form name="form" action="" method="POST" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="tableForm">

		<thead>
			<tr>
				<th>Please select a file and click Upload button</th>
			</tr>
		</thead>
		<tbody>	
			<tr>
				<td><input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input"></td>			</tr>

		</tbody>
			<tfoot>
				<tr>
					<td><button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button></td>
				</tr>
			</tfoot>
	
	</table>
		</form>    	
      
      <textarea id="publish_content" rows="5" cols="40" onkeypress="updateContentRest();"></textarea><br/>
      <span id='content_rest'>你可以输入140字</span><br/>
      <input type="checkbox" id="weibo_cb" style="visibility:hidden"/>新浪微博  &nbsp;&nbsp;|&nbsp;&nbsp; 
      <input type="checkbox" id="qq_cb"/ style="visibility:hidden">QQ空间  &nbsp;&nbsp;|&nbsp;&nbsp; 
      <input type="checkbox" id="renren_cb" style="visibility:hidden"/>人人  &nbsp;&nbsp;|&nbsp;&nbsp;  
      <input type="checkbox" id="facebook_cb" style="visibility:hidden"/>facebook   
      <br/>
      <input type="button" value="发布" onclick="publish();"/><br/>
      
      </div>
      
      <div style="width:30%;float:left;border-radius:9px;background-color:#CCC;">
      <span style="color:blue;font-weight:bold;">新浪微博</span>
      <div id="weibo_newsfeed">
      </div>
	  </div>
	     
      <div style="float:left;">
         &nbsp;&nbsp;
      </div>

      <div style="width:30%;float:left;border-radius:9px;background-color:#CCC;">
      <span style="color:blue;font-weight:bold;">人人校内</span>
      <div id="renren_newsfeed">
      </div>
      </div>
         
      <div style="float:left;">
      &nbsp;&nbsp;
      </div>

      <div style="width:30%;float:left;border-radius:9px;background-color:#CCC;">
      <span style="color:blue;font-weight:bold;">Facebook</span>
      <div id="facebook_newsfeed">
      </div>
      </div>
         
      
      
</body>
</html>