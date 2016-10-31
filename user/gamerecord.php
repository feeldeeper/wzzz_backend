<?php 
$username = $_GET['username'];
$pwd = $_GET['pwd'];
if(!isset($username))
	$username="";
if(!isset($pwd))
	$pwd="";
else
	$pwd=md5($pwd);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="keywords" content="王者至尊 王者至尊三合一 王者至尊开户 王者至尊官方网站 王者至尊官方信誉担保 王者至尊会员 王者至尊代理 王者至尊电话">
<meta name="description" content="王者至尊 王者至尊三合一 官方网站，是唯一可现场电话验证的官方网上推广平台，官方信誉担保，大额无忧，假一赔百！您可致电指定我们到现场台面验证视频真假，不限次数。">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>王者至尊 王者至尊三合一 官方网站</title>
<link rel="stylesheet" href="/user/css/records.css" type="text/css" />
<script type="text/javascript" src="/user/js/jquery.js"></script>
</head>
<body>
<div id="main">
	<div id="top">
		<div id="tleft">
			<div id="q1">過往結果
			</div>
			<div id="q2"><span id="qsta">數據加載中...</span>
			</div>
		</div>
		
		<div id="tright">
			<div id="q5">
			<input type="button" onclick="jsonRefresh()" value="刷新数据"/>
			</div>
			
			<div id="q3">日期</div>	
			
			<div id="q4">
				<div class="zselect">
					<ul>
						<li class="on"><?php echo date("Y-m-d");?></li>
					<?php for($i=1;$i<30;$i++){?>
						<li><?php echo date("Y-m-d",time()-24*3600*$i);?></li>
					<?php } ?>
					</ul>
				</div>
			</div>
			
			<div id="q3">页数
			</div>
			<div id="q6">
			<input class="pagestart" onclick="jsonRefresh()" type="button" value=""/>
			<input class="pagepre" onclick="jsonRefresh()" type="button" value=""/>
			</div>
			<div id="q61">
				<span class="pagetxt">1/1</span>
			</div>
			<div id="q66">
			<input class="pagenext" onclick="jsonRefresh()" type="button" value=""/>
			<input class="pageend" onclick="jsonRefresh()" type="button" value=""/>
			</div>
			<div id="q7">
			<input type="button"  value="关闭"/>
			</div>
		</div>
	</div>
	<div id="bottom">
	<table cellspacing="1" cellpadding="0" >
		<tr style="background:#b6b6b6;color:#0b333c;">
			<td>投注編碼</td>
			<td>日期</td>
			<td>時間</td>
			<td>遊戲</td>
			<td>臺</td>
			<td>局數</td>
			<td>結果</td>
			<td>投注碼</td>
			<td>投注額</td>
			<td>輸/贏</td>
			<td>狀態</td>
		</tr>
	</table>
<div id='userList'>
<?php $odd=0; for($i=0;$i<17;$i++){$odd++; if($i%2==1){}?>
	<table cellspacing="1" cellpadding="0" id="template">
		<tr style="background:<?php if($i%2==0){echo "#e0e0e0";}else{ echo "#f3f3f3"; } ?>;">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
		<?php } ?>
</div>	
	<table cellspacing="1" cellpadding="0" id="template" style="display:none">
		<tr style="background:#e0e0e0;color:#0b333c;"  align="center">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<input type="hidden" id="username" value="<?php echo $username;?>">
	<input type="hidden" id="pwd" value="<?php echo $pwd;?>">
	
	</div>
</div>
<script>
	jsonRefresh();
    var lis = document.getElementsByClassName('zselect')[0].getElementsByTagName('li');

    for(var i = 0; i < lis.length; i++){
        lis[i].onclick = function(){

            if(this.parentNode.getAttribute('_zxs') == 'show'){
                //for(var j = 0; j < lis.length; j++){ lis[j].className = '';}
                for(var j = 0; j < lis.length; j++){ lis[j].style.display = 'none';
				}
				$("li").removeClass("on");
                this.className = 'on';
                this.style.display = 'block';
                this.parentNode.setAttribute('_zxs','hide');
				jsonRefresh();
            }else{
                //for(var j = 0; j < lis.length; j++){ lis[j].className = 'on';}
                for(var j = 0; j < lis.length; j++){ lis[j].style.display = 'block';}
				$(".zselect").css("height","300px");
				$(".zselect").css("overflow-y","auto");
				// this.parentNode.style.overfolow
                this.parentNode.setAttribute('_zxs','show');
            }
        };
    }

	
function jsonRefresh(){
$("#qsta").html("數據加載中...");
$.post("/user/recordjson.php", {username:$("#username").val(),pwd:$("#pwd").val(),date:$(".on").html()},
   function(data){
	  $("#userList").html("");

	  // userNum = data.length;
	  $.each(data, function(i, item){
		var template = $("#template").clone(true);
		template.removeAttr("id");

		if(	(i % 2) == 1)
		{
			template.find("tr").eq(0).css('background','#f3f3f3');
		}
		template.show();

		template.find("td").eq(0).html(item.gameNum);
		template.find("td").eq(1).html(item.date);
		template.find("td").eq(2).html(item.time);
		template.find("td").eq(3).html(item.game);
		template.find("td").eq(4).html(item.tablet);
		template.find("td").eq(5).html(item.round);
		template.find("td").eq(6).html(item.result);
		template.find("td").eq(7).html(item.ma);
		template.find("td").eq(8).html(item.money);
		template.find("td").eq(9).html(item.syh);
		template.find("td").eq(10).html(item.status);
		
		$("#userList").append(template);
	  });
	  if(data.length<17)
	  {
		var len = 17-data.length;
		for(var i=0;i<len;i++)
		{
			var template = $("#template").clone(true);
			template.removeAttr("id");

			if(	((i+len) % 2) == 0)
			{
				template.find("tr").eq(0).css('background','#f3f3f3');
			}
			template.show();
			$("#userList").append(template);
		}
	  }
	  if(data.length==0)
		$("#qsta").html("暫無數據");
	  else
		$("#qsta").html("加載完成");
   }, 'json');
}
</script>
</body>
</html>