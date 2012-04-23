
var APP={
		
		log:function(data) {
		    console.log(data);
		},
		
		initIndexPage:function(){
//			$("#MAIN_MENU").tabs();
		    
		    var loginButtons = $(".login_button");
		    
		    loginButtons.mouseover(function(e){
		        this.style.backgroundColor="#666";
		    });
            loginButtons.mouseout(function(e){
                this.style.backgroundColor="";
//                this.style.opacity = 0.5;
//                this.style.filter = 'alpha(opacity=' + 50 + ')';
            });

		},
		
		initHomePage:function() {
		    $('#LOADING_DIV').remove();
            $('#MAIN_MENU').css('visibility','visible');
		    $("#MAIN_MENU").tabs();
		},
		
		showGameList:function() {
		    var content = '<div id="GAME_CANVAS" style="margin:0 auto;width:800px;height:600px;border:1px solid white;font-size:14px;">' +
		      '<table>'+
		      '<tr><td>'+
		      ' <img style="cursor:pointer;" src="./image/touchnumber.jpg" width="120px" height="80px" onclick="APP.startGame(\'./games/touchnumber/index.php\');"/>'+
		      ' </td><td>(单人游戏)<br/><br/>数触,用最快时间按照顺序消除数字圆球,你的成绩将会和其它玩家比较排名</span></td></tr>'+
		      ' <tr><td>'+
		      ' <img style="cursor:pointer;" src="./image/touch2.jpg" width="120px" height="80px" onclick="APP.startGame(\'./games/touch2/index.php\');"/>'+
		      ' </td><td><span>(双人游戏)<br/><br/>数触对战,和世界其它玩家抢赛，看谁能用最快速度消除最多的数字</span></td></tr>'+
		      '</table>'+ 
		      '</div>';

		    $('#MAIN_MENU-2').html(content);

		},
		
		startGame:function(div, url) {
		    $('#' + div).html('<iframe src="' + url + '" width="800" height="600" scrolling="no"></iframe>');
		},
		
		startTouch2Game:function() {
		    APP.startGame('touch2_GAME_CANVAS','./games/touch2/index.php');  
		},
		
		showSlidePuzzle:function() {
		    APP.startGame('slidepuzzle_GAME_CANVAS','./games/slidepuzzle/index.php');
		},

        showRow5:function() {
	            APP.startGame('row5_GAME_CANVAS','./games/row5/index.php');
	    },

	    showTouch2Summary:function() {
	        var content = "<table>" +
            '<tr><td>' +
            '<img style="cursor:pointer;" src="./image/touch2.jpg" width="350px" height="200px" onclick="APP.startTouch2Game();"/>'+
            '</td><td>'+
            '点点看是一个简单快速考验你眼力反应力的休闲游戏。<br/>'+
            '游戏中会随机出现25张各种风格的图片，玩家需要根据提示去选取正确图片，最快选完正确图片的玩家获得胜利<br/>'+
            '游戏流程：玩家首先自定义一个游戏房间名，创建游戏，然后进入游戏。若无其他人加入，游戏为单人模式<br/>'+
             '                            在游戏第一页可以看到当前所有可以选取的游戏。如果玩家不想自己创建，则可点击游戏名，开始多人游戏。'+
            '</td></tr></table>'+
            '<table>'+
            '<tr><td>单人游戏得分排名</td><td>多人比赛积分排名</td></tr>'+
            '<tr><td>'+
            '  <div id="touchnumber_SCORE_LIST" style="font-size:11px;width:375px;height:400px;overflow:auto;">'+
            '  </div>'+
            '</td>'+
            '<td>'+
            '  <div id="touch2_RANK_LIST" style="width:350px;height:400px;overflow:auto;">'+
            '  </div>'+
            '</td>'+
            '</tr>'+
            '</table>';
	        
	        $('#touch2_GAME_CANVAS').html(content);
	        
	        NewsFeed.getNewsFeed();
        },

		
		openNewWindow: function(url) {
		    if (url!=undefined && url.length>1) {
                var w = window.open (url,"www.weibospace.com");
		    } 

		},
		
		qqLogin: function(userId) {
		  window.location.href="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=100260402&" +
		                       "redirect_uri=" + escape("http://www.weibospace.com/phpservice/registeruser.php?type=qq") +
		                       "&scope=get_user_info,add_share,add_t,add_pic_t";
		},
		
		weiboLogin: function(userId) {
		    window.location.href="https://api.weibo.com/oauth2/authorize?client_id=739932394&response_type=code&redirect_uri=" + escape("http://www.weibospace.com/phpservice/registeruser.php?type=weibo");
		},
		
		renrenLogin:function() {
		  window.location.href="https://graph.renren.com/oauth/authorize?client_id=187465&response_type=code&redirect_uri=" + 
		                        escape("http://www.weibospace.com/phpservice/registeruser.php?type=renren") + "&display=page&scope=status_update,publish_share,photo_upload,read_user_status";  
		},
		
		facebookLogin:function() {
		    window.location.href="https://www.facebook.com/dialog/oauth?client_id=214122235315121&redirect_uri=" + 
		                         escape("http://www.weibospace.com/phpservice/registeruser.php?type=facebook") +
		                         "&scope=email,read_stream,status_update,publish_stream,offline_access";
		}
		
};
