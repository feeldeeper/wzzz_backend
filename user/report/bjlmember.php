
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/skin/system.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skin/blue/style.css" type="text/css" media="print, projection, screen" />
<script type="text/javaScript" src="/js/jquery.js"></script>
<script type="text/javaScript" src="/js/common.js"></script>
<script type="text/javaScript" src="/js/jquery.tablesorter.min.js"></script>
<title></title>
<style type="text/css">
.table_list td{ text-align:center}
</style>
<script type="text/javascript">
window.onerror = function(){
    return true;
}
$(function() {
	$("#listTable").tablesorter({sortList:[[5,1]]});
});
function act(url, flag){
	switch (flag) {
		default:
			str = "ȷ��Ҫ��������"
			break;
		case 2:
			str = "���ɾ���ϼ��Ļ��������¼������л�Ա�ʹ����ᱻɾ����\n���Ǳ������Ϸ���ݲ���ɾ����";
			break;
		case 3:
			str = "����߳����ǻ�Ա�Ļ��������һ����Ϸ��ʼ���Զ���Ч��\nȷ��Ҫ�߳��𣿣�";
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
</script>
</head>
<body>
<DIV id=position2><STRONG>��ǰλ�ã�</STRONG>
<A href='/report/live/index/stime/2015-07-21+08%3A00%3A00/etime/2015-07-22+08%3A00%3A00'></A></DIV>
<table cellpadding="0" cellspacing="1" class="table_info">
<caption>��ݲ���</caption>
    <tr>
		<td>
<input type='button' class="button_style" onclick='history.go(-1);' value='��������һҳ'>
<input type='button' class="button_style" onclick='history.go(1);' value='ǰ������һҳ'>
<input type='button' class="button_style" onclick='location.reload();' value='ˢ�±�ҳ'>
	  </td>
	</tr>
    <tr>
    	<td>
���û�����ѯ��
  <input name="userName" type="text" id="userName" onKeyDown="if(event.keyCode==13) {go();}" value="" size="20"/>
<link rel="stylesheet" type="text/css" href="/js/calendar/calendar-blue.css"/>
<script type="text/javascript" src="/js/calendar/calendar.js"></script>
��ʼʱ�䣺<input name="sTime" type="text" id="sTime" value="2015-07-21 08:00:00" size="18" />
  ����ʱ�䣺
  <input name="eTime" type="text" id="eTime" value="2015-07-22 08:00:00" size="18" />
  <input type='button' class="button_style" onclick="go()" value="ȷ������">
<a href="javascript:settime('2015-08-03 08:00:00','2015-08-10 08:00:00')">����</a> <a href="javascript:settime('2015-08-13 08:00:00','2015-08-14 08:00:00')">����</a> <a href="javascript:settime('2015-08-14 08:00:00','2015-08-15 08:00:00')">����</a> <a href="javascript:settime('2015-08-10 08:00:00','2015-08-17 08:00:00')">����</a> <a href="javascript:settime('2015-08-03 08:00:00','2015-09-07 08:00:00')">����</a>
      </td>
    </tr>
</table>

<table cellpadding="0" cellspacing="1" class="table_list" id="listTable">
<caption>
�ټ��ֻ�Ա����
</caption>
<thead>
<tr align="center">
  <th>&nbsp;</th>
	<th>�û���</th>
	<th>����</th>
    <th>Ͷע����</th>
	<th>Ͷע���</th>
	<th>��Ӯ���</th>
	<th>ϴ����</th>
	<th>ϴ���(%)</th>
	<th>ϴ��Ӷ��</th>
	<th>�ܽ��</th>
	</tr>
</thead>
<tbody>
</tbody>
<tfoot>
<tr align="center" id="total_listTable" class="total">
  <th>�ϼ�</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  <th>&nbsp;</th>
  </tr>
</tfoot>
</table>
<div style="margin:5px; float:left"><input type='button' class="button_style" onclick="window.print()" value="��ӡ"></div>
<script type="text/javascript">
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
function settime(time1,time2){
	$("#sTime").val(time1);
	$("#eTime").val(time2);
	go(0);
}
function setKeyWord(obj){
	$("#keyWord").val(obj.innerHTML);
	go(0);
}
function go(page){
	var userName = $("#userName").val();
	var userId = getUrlQuery('id', 3);
	if (userName != '') {
		var url = "/report/live/search/key/"+$("#userName").val()+"/stime/"+$("#sTime").val()+"/etime/"+$("#eTime").val()+"/type/0";
	} else {
		var url = "/report/live/member/id/"+userId+"/stime/"+$("#sTime").val()+"/etime/"+$("#eTime").val()+"/type/0";
	}
	redirect(url);
}
function getResult(id, num)
  {
   var result = 0;
   var list =  $('#'+id+' tbody td:nth-child('+num+')');
    $.each(list,function(i,n)
	{
		result +=  parseFloat(n.innerText);
	});
	return result;
  }
printTotal('listTable', 12);
printTotal('listTable', 4);
printTotal('listTable', 5);
printTotal('listTable', 6);
printTotal('listTable', 7);
printTotal('listTable', 9);
printTotal('listTable', 10);
function printTotal(obj, num){
	var val = getResult(obj, num);
	$('#total_' + obj).find('th').eq(num - 1).text(val);
}
//$("#pTotal").html(getResult('listTable', 11));
</script>
<table cellpadding="0" cellspacing="1" class="table_info">
  <caption>����ʼ������ʱ����ʾ��Ϣ</caption>
  <tr>
    <td>
1��ÿ�죺��ʼʱ���Ǵ�����12�������ڶ�������11��59��59�룬����ʱ���Ҳ�ǰ��˸�ʽ��<br />
2��ÿ�ܣ��ӵ�ǰʱ�������ܵ���һ�����ա�<br />
3��ÿ�£��ӵ�ǰʱ�������µĵ�һ�ܵ�����һ��������4�����ڵ�����һ��
	</td>
  </tr>
</table>
</body>
</html>
<!--
http://bg.88gobo.net/report/live/bjlmember/id/5102584/stime/2015-05-21%2008:00:00/etime/2015-07-22%2008:00:00
62328
-->