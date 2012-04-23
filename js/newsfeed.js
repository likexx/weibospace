
var NewsFeed ={
  
   socket:null,
   
   users:{},
   
   init:function(){
       socket = io.connect('http://106.187.43.45:54321/singleplayer');
       
       socket.on('CONNECTED', function(data){
//           NewsFeed.getNewsFeed();
       });
       
       socket.on('UPDATE_SCORE_LIST',function(data){
           NewsFeed.updateScoreList(data);
       });

       socket.on('UPDATE_RANK_LIST',function(data){
           NewsFeed.updateRankList(data);
       });

       socket.on('NEW_GAME_SCORE',function(data){
           NewsFeed.showNewGameScore(data);
       });

   },

   
   getNewsFeed: function() {
       /*
       socket.emit('GET_SCORE_LIST',{'gamename':'touchnumber'});
       socket.emit('GET_RANK_LIST',{'gamename':'touch2'});
       */
       $.post('/phpservice/getlist.php', {gamename:'touchnumber', action:'GET_SCORE_LIST'},function(data){
           NewsFeed.updateScoreList(YAHOO.lang.JSON.parse(data));
       });

       $.post('/phpservice/getlist.php', {gamename:'touch2', action:'GET_RANK_LIST'},function(data){
           NewsFeed.updateRankList(YAHOO.lang.JSON.parse(data));
       });

   },
   
   updateScoreList:function(d) {
       var divname= d.gamename+'_SCORE_LIST';
       
       var records = d.records;
       
       var content="<table style='border-size:0;font-size:12px;color:black;'><tr style='color:white;font-size:14px;'><td style='width:50px;'>名次</td><td style='width:120px;'>用户</td><td style='width:40px;'>用时</td><td>时间</td></tr>";
       for(i in records) {
           var r = records[i];
           var time = new Date(r.time);
           var color = i%2 == 0 ? 'white':'#cccccc';
           content+="<tr style='background-color:" + color + ";'><td style='vertical-align:bottom;'>" + (parseInt(i)+1) + "</td>" +
                    "<td style='vertical-align:bottom;'><span id='username_" + r._id + "'>" + NewsFeed.getUserData(r._id, r.userid) + "</span></td>" + 
                    "<td style='vertical-align:bottom;'>" + r.score + "</td>" +
                    "<td style='vertical-align:bottom;'>" + time.getFullYear() + "-" + (time.getMonth()+1) + "-" + time.getDate() + " " + time.getHours() + ":" + time.getMinutes() + "</td></tr>";
          
       }
       
       content+="</table>";
       
       $('#' + divname).html(content);
   },

   updateRankList:function(d) {
       var divname= d.gamename+'_RANK_LIST';
       
       var records = d.records;
       
       var content="<table style='border-size:0;font-size:12px;color:black;'><tr style='color:white;font-size:14px;'><td style='width:50px;'>排名</td><td style='width:120px;'>用户</td><td style='width:40px;'>积分</td></tr>";
       for(i in records) {
           var r = records[i];
           var time = new Date(r.time);
           var color = i%2 == 0 ? 'white':'#cccccc';
           content+="<tr style='background-color:" + color + ";'><td style='vertical-align:bottom;'>" + (parseInt(i)+1) + "</td>" +
                    "<td style='vertical-align:bottom;'><span id='username_" + r._id + "'>" + NewsFeed.getUserData(r._id, r.userid) + "</span></td>" + 
                    "<td style='vertical-align:bottom;'>" + r.exp + "</td></tr>";          
       }
       
       content+="</table>";
       
       $('#' + divname).html(content);
   },

   
   getUserData:function(rid, uid) {
       if (this.users[uid]!=null && this.users[uid]!=undefined) {
           var user = this.users[uid];
           return "<img src='" + user.image + "'/>" + user.username;
       }
       
       $.post('/getuser.php',{uid:uid}, function(data) {
           var user=YAHOO.lang.JSON.parse(data);
           var content =  "<img src='" + user.image + "'/>" + user.username;
           $('#username_' + rid).html(content);
           NewsFeed.users[uid] = user;
       });
       
       return "";
   },

   
   showNewGameScore:function(d) {
       
       var user = d.user;
       var record = d.record;
       var uid = user._id;
       
       if (this.users[uid]==null || this.users[uid]==undefined) {
           this.uers[uid] = user;
           return;
       }
       
       NewsFeed.createRealtimeScore(user, record);
       
   },
   
   createRealtimeScore: function(user, record) {
       var divname = "REALTIME_GAME_SCORE";
       
       var prevContent =   "<img src='" + user.image + "'/>" + user.username +
                           "完成游戏" + record.game + ", 用时" + record.score + "<br/>" +
                           $('#'+divname).html();
       $('#'+divname).html(prevContent);
       
   }
   
   
   
        
};