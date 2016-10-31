$(function() {
date = new Date();
	Calendar.setup({
		inputField     :    "sTime",
		ifFormat       :    "%Y-%m-%d %H:%M:%S",
		showsTime      :    true,
		align          :    "B1",
		singleClick    :    true
	});
	Calendar.setup({
		inputField     :    "eTime",
		ifFormat       :    "%Y-%m-%d %H:%M:%S",
		showsTime      :    true,
		align          :    "B1",
		singleClick    :    true
	});
	$('#loading').fadeOut('slow');
	var modal = getArgs("modal");
	if (modal == "true") {
		$("#fullScreen").click(function() {
			var url = parent.$("#TB_iframeContent").attr("src").replace("&modal=true", "");
			parent.$("#right").attr("src", url);
			parent.tb_remove();
		});
		$("#fullScreen").val("退出全屏");
	} else {
		$("#fullScreen").click(function() {
			window.parent.showIframe(window.location.href);
		});
	}
	// if ($("#listTable tbody").find("tr").length > 0) {
		// $("#listTable").tablesorter({sortList:[[0,-1]]});
	// }
	// if ($("#mlistTable tbody").find("tr").length > 0) {
		// $("#mlistTable").tablesorter({sortList:[[6,0]]});
	// }
printTotal('listTable', 5);
printTotal('listTable', 6);
printTotal('listTable', 7);
printTotal('listTable', 8);
});
function act(url, flag){
	switch (flag) {
		default:
			str = "确定要这样做吗？"
			break;
		case 2:
			str = "如果删除上级的话，那他下级的所有会员和代理都会被删除！\n但是报表和游戏数据不会删除！";
			break;
		case 3:
			str = "如果踢出的是会员的话，需等下一局游戏开始才自动生效！\n确定要踢出吗？！";
			break;
	}
	if(flag > 0){
		var bln = window.confirm(str);
		if(bln != false){
			document.myform.action = url;
			$("#myform").submit();
		}
	}
}
function checkPost(){
	
}


function redirect(url)
{
	if(url.lastIndexOf('/.') > 0)
	{
		url = url.replace(/\/(\.[a-zA-Z]+)([0-9]+)$/g, "/$2$1");
	}
	else if(url.match(/\/([a-z]+).html([0-9]+)$/)) {
		url = url.replace(/\/([a-z]+).html([0-9]+)$/, "/$1-$2.html");
	}
	else if(url.match(/-.html([0-9]+)$/)) {
		url = url.replace(/-.html([0-9]+)$/, "-$1.html");
	}

	if(url.indexOf('://') == -1 && url.substr(0, 1) != '/' && url.substr(0, 1) != '?') url = $('base').attr('href')+url;
	var url = url + "&keepThis="+getArgs("keepThis")+"&modal="+getArgs("modal");
	if (getArgs("modal") == "true") {
		parent.$("#TB_iframeContent").attr("src", url);
	} else {
		location.href = url;
	}
}

function getArgs(strParame) {
var args = new Object();
var query = location.search.substring(1); // Get query string
var pairs = query.split("&"); // Break at ampersand
for(var i = 0; i < pairs.length; i++) {
var pos = pairs[i].indexOf('='); // Look for "name=value"
if (pos == -1) continue; // If not found, skip
var argname = pairs[i].substring(0,pos); // Extract the name
var value = pairs[i].substring(pos+1); // Extract the value
value = decodeURIComponent(value); // Decode it, if needed
args[argname] = value; // Store as a property
}
return args[strParame]; // Return the object
}