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
<meta name="keywords" content="�������� ������������һ �������𿪻� ��������ٷ���վ ��������ٷ��������� ���������Ա ����������� ��������绰">
<meta name="description" content="�������� ������������һ �ٷ���վ����Ψһ���ֳ��绰��֤�Ĺٷ������ƹ�ƽ̨���ٷ�����������������ǣ���һ��٣������µ�ָ�����ǵ��ֳ�̨����֤��Ƶ��٣����޴�����">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�������� ������������һ �ٷ���վ</title>
<link rel="stylesheet" href="/user/css/records.css" type="text/css" />
<script type="text/javascript" src="/user/js/jquery.js"></script>
</head>
<body>
<div id="main">
	<div id="top">
		<div id="tleft">
			<div id="q1">�^���Y��
			</div>
			<div id="q2"><span id="qsta">�������d��...</span>
			</div>
		</div>
		
		<div id="tright">
			<div id="q5">
			<input type="button" onclick="jsonRefresh()" value="ˢ������"/>
			</div>
			
			<div id="q3">����</div>	
			
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
			
			<div id="q3">ҳ��
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
			<input type="button"  value="�ر�"/>
			</div>
		</div>
	</div>
	<div id="bottom">
	<table cellspacing="1" cellpadding="0" >
		<tr style="background:#b6b6b6;color:#0b333c;">
			<td>Ͷע���a</td>
			<td>����</td>
			<td>�r�g</td>
			<td>�[��</td>
			<td>�_</td>
			<td>�֔�</td>
			<td>�Y��</td>
			<td>Ͷע�a</td>
			<td>Ͷע�~</td>
			<td>ݔ/�A</td>
			<td>��B</td>
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
$("#qsta").html("�������d��...");
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
		$("#qsta").html("���o����");
	  else
		$("#qsta").html("���d���");
   }, 'json');
}
</script>
</body>
</html>