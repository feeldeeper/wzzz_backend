<?php date_default_timezone_set('PRC');
$mo = $_GET['s_moudle'];
if (isset($_GET['s_username'])) {
    $user = $_GET['s_username'];
} else {
    $user = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
<link href="/user/css/system.css" rel="stylesheet" type="text/css">
<script type="text/javaScript" src="/user/js/jquery.js"></script>
<script type="text/javaScript" src="/user/js/common.js"></script>
<script type="text/javascript" src="/user/js/jquery.liu.select.js"></script>
<style type="text/css">
    .align_c td {
        text-align: center
    }

    .align_l th {
        text-align: left
    }
</style>
</head>
<body>
<table cellpadding="0" cellspacing="1" class="table_list">
    <caption>日志搜索</caption>
    <tr>
        <th><strong>事件</strong></th>
        <th><STRONG>用户名</STRONG></th>
        <th><strong>操作人</strong></th>
        <th><strong>地区</strong></th>
        <th><strong>操作内容</strong></th>
        <th><strong>IP</strong></th>
        <th><strong>起始日期</strong></th>
        <th><strong>截止日期</strong></th>
        <th><strong>查询</strong></th>
    </tr>
    <tr>
        <td>
            <input name="mod" type="hidden" size="15" value="phpcms">
            <input name="file" type="hidden" size="15" value="log">
            <input name="action" type="hidden" size="15" value="manage">
            <select name="s_module" id="s_module">
                <option value=''>不限</option>
                <option value='8' <?php if ($mo == "8") echo "selected"; ?>>登陆注销</option>
                <option value='9' <?php if ($mo == "9") echo "selected"; ?>>锁定操作</option>
                <option value='10'<?php if ($mo == "10") echo "selected"; ?>>加减金额</option>
                <option value='13'<?php if ($mo == "13") echo "selected"; ?>>账号操作</option>
                <option value='14'<?php if ($mo == "14") echo "selected"; ?>>编辑账户日志</option>
                <option value='15'<?php if ($mo == "15") echo "selected"; ?>>删除账户日志</option>
            </select>
        </td>
        <td><input name="s_username" id="s_username" value="<?= $user ?>" size="12"></td>
        <td><input name="s_operator" id="s_operator" value="" size="12"></td>
        <td>
            <div id="city"></div>
            <script type="text/javascript" src="/user/js/jquery.provincesCity.js"></script>
            <script type="text/javascript" src="/user/js/provincesdata.js"></script>
            <link rel="stylesheet" type="text/css" href="/user/css/calendar-blue.css"/>
            <script type="text/javascript" src="/user/js/calendar.js"></script>
            <script type="text/javaScript" src="/user/js/log.js"></script>
        </td>
        <td><input name="s_msg" id="s_msg" value="" size="12"></td>
        <td><input name="s_ip" id="s_ip" value="" size="12"></td>
        <td><input type="text" name="s_fromdate" id="s_fromdate" value="" size="19" readonly>&nbsp;
            <script language="javascript" type="text/javascript">
                date = new Date();
                document.getElementById ("s_fromdate").value = "<?php echo date("Y-m-d",strtotime("-1 day"))?> 08:00:00";
                Calendar.setup({
                    inputField: "s_fromdate",
                    ifFormat: "%Y-%m-%d %H:%M:%S",
                    showsTime: true,
                    align: "B1",
                    singleClick: true,
                    timeFormat: "24"
                });
            </script>
        </td>
        <td><input type="text" name="s_todate" id="s_todate" value="" size="19" readonly>&nbsp;
            <script language="javascript" type="text/javascript">
                date = new Date();
                document.getElementById ("s_todate").value = "<?php echo date("Y-m-d",time())?> 23:59:59";
                Calendar.setup({
                    inputField: "s_todate",
                    ifFormat: "%Y-%m-%d %H:%M:%S",
                    showsTime: true,
                    align: "B1",
                    singleClick: true,
                    timeFormat: "24"
                });
            </script>
        </td>
        <td>
            <input type="submit" onClick="go()" value="查询">
            <input type="submit" onClick="redirect('/user/log/index.php')" value="清空条件">
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="1" class="table_list align_c">
    <caption> 日志记录</caption>
    </tr>
    <tr>
        <th width="5%"><strong>ID</strong></th>
        <th width="10%"><strong>用户名</strong></th>
        <th width="10%">操作人</th>
        <th><strong>事件</strong></th>
        <th>金额</th>
        <th>余额</th>
        <th><strong>操作内容</strong></th>
        <th><strong>地区</strong></th>
        <th><strong>时间</strong></th>
        <th><strong>IP</strong></th>
    </tr>
    <tbody>
    <tr>
        <td colspan="10" class="r bo">没有上述条件的数据，请重新选择查询参数。</td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <th>总计</th>
        <th></th>
        <th></th>
        <th></th>
        <th id="pTotal"></th>
        <th colspan="5" class="align_l">合计：</th>
    </tr>
    </tfoot>
</table>
<div id="pages">
</div>
<script type="text/javascript">
    $("#pTotal").html(ForDight(getResult(5), 2));
</script>
</body>
</html>