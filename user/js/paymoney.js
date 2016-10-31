$().ready(function() {
	$('form').checkForm(1);
	$("#balance1").html(agLiveMoney);
	$("#balance3").html(liveMoney);
	var w = window,d = document,n = navigator,k = document.getElementById("kw")
	if (w.attachEvent) {
		w.attachEvent("onload", function() {k.focus();})
	} else {
		w.addEventListener('load', function() {k.focus()},true)
	};
	var hw = {};
	hw.i = d.getElementById("sx");
	var il = false;
	if (/msie (\d+\.\d)/i.test(n.userAgent)) {
		hw.i.setAttribute("unselectable", "on")
	} else {
		var sL = k.value.length;
		k.selectionStart = sL;
		k.selectionEnd = sL
	}
	hw.i.onclick = function(B) {
		var B = B || w.event;
		B.stopPropagation ? B.stopPropagation() : (B.cancelBubble = true);
		if (d.selection && d.activeElement.id && d.activeElement.id == "kw") {
			hw.hasF = 1
		} else {
			if (!d.selection) {
				hw.hasF = 1
			}
		}
		if (!il) {
			var A = d.createElement("script");
			A.setAttribute("src", "http://www.baidu.com/hw/hwInput.js");
			d.getElementsByTagName("head")[0].appendChild(A);
			il = true;
		}
	};
});

function checkForm() {
var txt="确认操作吗？";
  if(confirm(txt)==true){
	if ($("input[name='type']:checked").val() == undefined)
	{
			alert('请选择加点或减点！');
			return false;
	}
	if ($("input[name='type']:checked").val() == 1) {
		if ($("#payInMoney").val() <= 0) {
			alert('操作金额必须大于0');
			return false;
		}
	} else {
		if ($("#payOutMoney").val() <= 0) {
			alert('操作金额必须大于0');
			return false;
		}
	}
	$('#postBtn').attr('disabled', 'disabled');
    $('#postForm').submit();
  } else {
    return false;
  }
} 
function change(msg)
{
    if(msg == 1)
    {
        $('#numberid').html("元");
    }
    else
    {
        $('#numberid').html("点");
    }
}

function selectPayType(type){
	if (type == 1) {
		$("#payIn").hide();
		$("#payOut").show();
	} else {
		$("#payIn").show();
		$("#payOut").hide();
	}
}
function pay(){
	if ($("input[name='type']:checked").val() == 1) {
		var temp = agLiveMoney - $("#payInMoney").val();
		if (temp < 0) {
			temp = 0;
		}
		$("#balance2").html('，现在还有余额：' + temp);
		$("#nowLiveMoney").html(' + ' + $("#payInMoney").val() + ' = ' + (parseFloat($("#liveMoney").text()) + parseFloat($("#payInMoney").val())));
	} else {
		var temp = liveMoney - $("#payOutMoney").val();
		if (temp < 0) {
			temp = 0;
		}
		$("#balance4").html('，现在还有余额：' + temp);
		$("#nowLiveMoney").html(' - ' + $("#payOutMoney").val() + ' = ' + (parseFloat($("#liveMoney").text()) - parseFloat($("#payOutMoney").val())));
	}
}