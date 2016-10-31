<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>路单</title>
    <link href="css/waybill.css" rel="stylesheet" />
    <script src="http://libs.baidu.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="js/jquery.waybill.js"></script>
</head>
<body style="margin:10px auto;width:655px;">
    <div id="test1" style="overflow:hidden;"></div>
	<div id="test2" style="overflow:hidden;padding-top:20px;"></div>
    <script>
        $(function () {
            $("#test1").Waybill();
			$("#test2").Waybill({isToradora:1});
        });
    </script>
</body>
</html>
