$(function(){
	$("#city").ProvinceCity();
	var city1 = getUrlQuery('s_city1');
	var city2 = getUrlQuery('s_city2');
	var city3 = getUrlQuery('s_city3');
	var s_fromdate = getUrlQuery('s_fromdate');
	var s_todate = getUrlQuery('s_todate');
	if (city1 != null) {
		$("#city1").setSelectedValue(decodeURIComponent(city1));
	}
	if (city2 != null) {
		var cityStr2 = decodeURIComponent(city2);
		$("#city2").addOption(cityStr2, cityStr2);
		$("#city2").removeIndex(0);
	}
	if (city3 != null) {
		var cityStr3 = decodeURIComponent(city3);
		$("#city3").addOption(cityStr3, cityStr3);
		$("#city3").removeIndex(0);
	}
	if (s_fromdate != null)
	{
		$("#s_fromdate").val(decodeURIComponent(s_fromdate));
	}
	if (s_todate != null)
	{
		$("#s_todate").val(decodeURIComponent(s_todate));
	}
});
function go(){
	var url = "/user/log/index.php?is_s=1&s_module="+$("#s_module").val()+"&s_username="+$("#s_username").val()+"&s_operator="+$("#s_operator").val()+"&s_city1="+encodeURIComponent($("#city1").val())+"&s_city2="+encodeURIComponent($("#city2").val())+"&s_city3="+encodeURIComponent($("#city3").val())+"&s_msg="+encodeURIComponent($("#s_msg").val())+"&s_ip="+$("#s_ip").val()+"&s_fromdate="+$("#s_fromdate").val().replace(= \| (.*)=ig, "")+"&s_todate="+$("#s_todate").val().replace(/ \| (.*)/ig, "");
	
	redirect(url);
}
var module = getUrlQuery('s_module');
if (module != null) {
	$("#s_module").setSelectedValue(module);
}
initKwd();
function initKwd(){
	setValue('s_username');
	setValue('s_operator');
	setValue('s_ip');
	setValue('s_fromdate');
	setValue('s_todate');
	setValue('s_msg');
}
function setValue(name){
	var value = decodeURIComponent(getUrlQuery(name));
	if (value != "null") {
		$("#" + name).val(value);
	}
}

function getResult(obj){
var result = 0;
var list =  $('tbody td:nth-child('+obj+')');
//alert(list.text());
$.each(list,function(i,n)
{
	if (n.innerText != '')
	{
		var tmp = parseFloat(n.innerText);
		result += tmp;
	}
});
return result;
}

function ForDight(Dight,How) {
	Dight = Math.round(Dight*Math.pow(10,How))/Math.pow(10,How);
	return Dight;
}