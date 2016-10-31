function ForDight(Dight,How) {
	Dight = Math.round(Dight*Math.pow(10,How))/Math.pow(10,How);
	return Dight;
}

function settime(time1,time2){
	$("#sTime").val(time1);
	$("#eTime").val(time2);
	go(0);
}
function setKeyWord(obj, type){
	$("#keyWord").val(obj.innerHTML);
	$("#s_mod").setSelectedValue(1);
	if (type == 0){
		$("#s_mod2").setSelectedValue(0);
	} else {
		$("#s_mod2").setSelectedValue(1);
	}
	go(0);
}
function go(page){
	$('#loading').show();
	if (page != 0) {
		var page = getUrlQuery('page', 3);
	}
	page = (page == null)?0:page;
	var url = "/user/gamelog/bjl.php?key="+$("#keyWord").val()+"&stime="+$("#sTime").val()+"&etime="+$("#eTime").val() + '&page=' + page + '&mod=' + $("#s_mod").val() + '&mod2=' + $("#s_mod2").val();
	redirect(url);
}
function getResult(obj)    
  {
   var result = 0;
   var list =  $('tbody td:nth-child('+obj+')');
    //alert(list.text());
    $.each(list,function(i,n)
	{
		//alert("name:"+i+"value:"+n.innerText);
		result +=  parseFloat(n.innerText);
 
	});
	return result;
  }

$(function() {
$('#loading').fadeOut('slow');
$("#pTotal").html(ForDight(getResult(14),2));
if (getUrlQuery('mod', 3)) {
	$("#s_mod").setSelectedValue(getUrlQuery('mod', 3));
} else {
	$("#s_mod").setSelectedValue(0);
}
if (getUrlQuery('mod2', 3)) {
	$("#s_mod2").setSelectedValue(getUrlQuery('mod2', 3));
} else {
	$("#s_mod2").setSelectedValue(1);
}
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

//$("#listTable").tablesorter({sortList:[[4,1]]});
	$(".table_list tr").click(
							function () {
									if ($(this).next().eq(0).find("img").length < 1) {
										var pokerDetail = poker[$(this).find("td").eq(0).text() - 1];
										if (pokerDetail) {
											var html = 'Player：<img src=/images/poker/s/' + pokerDetail[0] + '.gif><img src=/images/poker/s/' + pokerDetail[2] + '.gif><img src=/images/poker/s/' + pokerDetail[4] + '.gif><br />Banker：<img src=/images/poker/s/' + pokerDetail[1] + '.gif><img src=/images/poker/s/' + pokerDetail[3] + '.gif><img src=/images/poker/s/' + pokerDetail[5] + '.gif>';
											$(this).next().eq(0).find("td").eq(0).html(html);
											$(this).next().eq(0).show();
										}
									}
								}
						);
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
 
function getArgs(strParame) {
var args = new Object( );
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
	if (getArgs("modal") == "true") {
		parent.$("#TB_iframeContent").attr("src", url);
	} else {
		location.href = url;
	}
}