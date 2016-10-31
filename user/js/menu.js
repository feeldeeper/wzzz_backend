var refresh = 0;
var menuid = 0;
var groupid = 0;
function evet(){
	$(".tree_div").mouseover(function(){
		$("#tree_bg").show();
		var offset = $(this).offset();
		var offset3 = $("#tree_box").offset();
		var y = offset.top - offset3.top+2;
		$("#tree_bg").css({"top":y+'px',"*top":(y+2)+'px'});
	});
	$(".tree_div").mouseout(function(){$("#tree_bg").hide();});
	$(".tree_div").click(function(){
		$("#tree_bj").css("height","0px");
		$("#tree_bg").hide();
		var offset = $(this).offset();
		var offset3 = $("#tree_box").offset();
		var y = offset.top - offset3.top+2;
		$("#tree_click").show();
		$("#tree_click").css("top",y+"px");
	});
}

$(document).ready(function(){
	$(".menu").click(function(){
	$("#tree_click").hide();
	$("#tree_bg").hide();
	$("#menu_name").html($(this).attr('alt'));
	$(".menu").removeClass("selected");
	$(this).addClass("selected");});
});

function get_menu(id,obj,islen,groupid){
	if(islen==0)
	{
		$("#tree_box").css('top', 0);
	}
	if (islen==0)
	{
		menuid = id;
	}
	//$('#position').load('/menu/menupos/menuid/'+id);
	$("#"+obj).html('<img src="images/loading.gif" width="16" height="16" border="0" />');
	
	var touimg_src = '';
	var img_src = '';
	var mleft = 's';
	var regexp = /(.*)images\/elbow-end-plus/;
	if ($("#tree_"+id).css("display")=='none'){
		if (regexp.test($("#touimg_"+id).attr("src")))
		{
			touimg_src = 'elbow-end-minus.gif';
			mleft = 'ss';
		}else{
			touimg_src = 'elbow-minus.gif';
		}
		img_src = "folder-open.gif";
		$("#tree_"+id).show();
	}else{
		if (regexp.test($("#touimg_"+id).attr("src")))
		{
			touimg_src = 'elbow-end-plus.gif';
		}else{
			touimg_src = 'elbow-plus.gif';
		}
		img_src = "folder.gif";
		$("#tree_"+id).hide();
	}
	$("#touimg_"+id).attr('src','images/'+touimg_src);
	$("#img_"+id).attr('src','images/'+img_src);
	
	var cache_refresh = refresh ? 'false': 'true';
	var menuUrl;
	if (groupid > 0)
	{
		if (id == 0) {
			menuUrl = 'http://ag.88gobo.net/user/tree/index/';
		} else {
			menuUrl = 'http://ag.88gobo.net/user/tree/index/id/'+id+'/groupid/'+groupid;
		}
	} else {
		menuUrl = 'http://ag.88gobo.net/menu/getmenulist/menuid/'+id;
		groupid = 0;
	}
	menuUrl="/user/a.php?i="+id;
	$.ajax({type:'get', url:menuUrl, cache:false,dataType:'json', success:function(json){
	//alert(json);
	var htmls="";
	var isend ="tree_line";
	var open = new Array();
	var openGroup = new Array();
	var openmenuids = ['100','104','105','106','107','102','108'];//默认要展开的menuid
	$.each(json,function(i,n){
		if (json.max != null && n.name != undefined)
		{
			var click,img,touimg='';
			if(n.isfolder==1){
				if($.inArray(n.menuid,openmenuids)>=0)
				{
					open[i] = n.menuid;
				}
				if (n.isopen == 1) {
					open[i] = n.menuid;
					openGroup[i] = n.groupids;
				}
				img = "folder.gif";
				click = " get_menu("+n.menuid+",'tree_"+n.menuid+"',"+(islen+1)+", "+n.groupids+");";
				if(n.url !== '' && n.url != "#") {
					click += 'document.getElementById(\''+n.target+'\').src=\''+n.url+'\';';
				}
				if(n.menuid == json.max){
					touimg = "elbow-end-plus.gif";
					isend = "end";
				}else{
					touimg = "elbow-plus.gif";
				}
			}else{
				img = "leaf.gif";
				click = 'document.getElementById(\''+n.target+'\').src=\''+n.url+'\';';
				if (n.menuid == json.max)
				{
					touimg = 'elbow-end.gif';
				}else{
					touimg = 'elbow.gif';
				}
			}
			htmls += "<div class='tree_div' id='tree_div_"+n.menuid+"'>";
			var width = islen*16;
			htmls += '<span class="tree_img"><img onclick="'+click+'" src="images/'+touimg+'" id="touimg_'+n.menuid+'" width="16" height="18" border="0" /><img src="images/'+img+'" id="img_'+n.menuid+'" width="16" height="16" border="0" /></span><a href="'+n.url+'" target="'+n.target+'" class="tree_text">'+n.name+'</a></div>';
			if(n.isfolder==1){htmls +='<div id="tree_'+n.menuid+'" class="'+isend+'" style="display:none;"></div>';}
		}
	});
	if (htmls)
	{
		$("#"+obj).html(htmls);
	}else{
		$("#tree_"+id).hide();
	}
	if(open)
	{
	$.each(open,function(i,n){get_menu(n,'tree_'+n,1,openGroup[i])});
	}
	evet();
	}});
}

function menu_refresh()
{
	$('#menurefresh').attr('title', '点击刷新目录');
	$('#menurefresh').attr('alt', '点击刷新目录');
	$('#menurefresh').attr('src', 'images/refreshed.gif');
	if (menuid == 0) {
		get_menu(menuid, 'tree', 0, 1);
	} else {
		get_menu(menuid, 'tree', 0);
	}
}