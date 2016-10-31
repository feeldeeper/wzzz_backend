var isShowGameLog = false;
var lang;
function showGameLog(a, b, c)
{
	// alert('1');
	// $("#faqbg").css(z-index:1);
	$("#faqdiv").css("zIndex", "1");
	$("#faqbg").css("zIndex", "2");
	$("#gameLog").attr("src","/user/gamerecord.php?username="+a+"&pwd="+b);
}

function showGameLog1(a, b, c){ 
	$("#faqbg").css({display:"block",height:$(document).height()});
	$("#faqdiv").css("top", "0px");
	if (isShowGameLog == false) {
		//$("#gameLog").html('<object id="gameLog1" name="gameLog1" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="100%" height="100%"><param name="movie" value="http://static.aopclub.com:888/flbin/sun_user.swf?"' +  Math.random() +'/><param name="quality" value="high" /><param name="allowscriptaccess" value="always"/><param name="flashVars" value="u='+a+'&p='+b+'" /><embed src="http://static.aopclub.com:888/flbin/sun_user.swf?"' +  Math.random() +' quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="100%" height="100%" flashVars="u='+a+'&p='+b+'"></embed></object>');
		var gameLogSwf = {
			movie:"http://www.wzzz.com/assets/sun_user.jpg",
			width:"100%",
			height:"100%",
			id:"gameLog1",
			majorversion:"10.1",
			build:"0",
			menu: "true",
			allowfullscreen: "true",
			allowscriptaccess: "always",
			bgcolor: "#000",
			wmode: "window",
			flashvars:"u=" + a + "&p=" + b
		};
		UFO.create(gameLogSwf, "gameLog");
	} else {
		$("#gameLog1").css("z-index", 9999);
	}
	$("#gameLog1").css("z-index", 9999);
	isShowGameLog = true;
	lang = c;
	upLang();
}

function closeGameLog(){
	$("#faqbg").css("zIndex", "-1");
	$("#faqdiv").css("zIndex", "-1");
	// $("#gameLog").css("z-index", "-1");
	// $("#gameLog1").css("z-index", "-1");
}

function upLang(){
	getFlash("gameLog1").upLang(lang);
	//alert(lang);
}

function getFlash(movieName)
{
    if (window.document[movieName])
    {
        return window.document[movieName];
    }
    if (navigator.appName.indexOf("Microsoft Internet")==-1)
    {
        if (document.embeds && document.embeds[movieName])
            return document.embeds[movieName];
    }
    else 
    {
        return document.getElementById(movieName);
    }
}