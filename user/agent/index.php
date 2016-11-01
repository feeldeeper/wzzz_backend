<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/function.php");
include("../inc/conn.php");


session_set_cookie_params(SESSION_LIFE_TIME);
session_start();
$suid = $_SESSION['uid'];
if (isset($suid) && $suid != "" && $suid != "0") {
} else {
    echo "<script>alert('请先登录!');window.location='../login.php';</script>";
    exit();
}
$username = $_SESSION['adminname'];
$uid = $_GET['id'];
$user = $database->query("select * from user where id = $uid")->fetch();
$agent = $database->query("select * from user where pid = $uid and type =2")->fetchAll();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <link href="/user/css/system.css" rel="stylesheet" type="text/css">
    <script type="text/javaScript" src="/user/js/jquery.js"></script>
    <script type="text/javaScript" src="/user/js/common.js"></script>
    <script type="text/javaScript" src="/user/js/jquery.tips.compressed.js"></script>
    <script type="text/javascript" src="/user/js/default.js"></script>
    <style type="text/css">
        body {
            min-width: 1400px;
            width: 100%
        }

        .align_c td {
            text-align: center
        }

        #loading {
            z-index: 1;
            padding: 5px 10px;
            background: red;
            color: #fff;
            position: absolute;
            top: 0px;
            left: 0px
        }
    </style>
    <script type="text/javascript">
        $(function () {
            $('#loading').fadeOut('slow');
            $("a", "#userList").tips();
            $("#userList").find("tr").each(function () {
                var tmp = $(this).find("td").eq(4);
                tmp.text(outputMoney(tmp.text()));
            });
        });
        function lock(id, type, obj) {
            var str;
            if (type == 1) {
                str = '开启';
            } else {
                str = '锁定';
            }
            var userName = $(obj).parents("tr").find("td").eq(1).text();
            if (confirm("确认要" + str + "【" + userName + "】的登录权限吗？")) {
                redirect('/user/manage/lock/id/' + id + '/type/' + type + '/username/' + userName);
            }
        }
        function liveLock(id, type, obj) {
            var str;
            if (type == 1) {
                str = '开启';
            } else {
                str = '锁定';
            }
            var userName = $(obj).parents("tr").find("td").eq(1).text();
            if (confirm("确认要" + str + "【" + userName + "】的投注权限吗？")) {
                redirect('/user/manage/lock/model/1/id/' + id + '/type/' + type + '/username/' + userName);
            }
        }
        function hideLock(id, type, obj) {
            redirect('/user/manage/hide/model/1/id/' + id + '/type/' + type + '/username/' + $(obj).parents("tr").find("td").eq(1).text());
        }
        window.onerror = function () {
            return true;
        }
    </script>
</head>
<body>
<div id='loading'>正在加载...</div>
<DIV id=position2><STRONG>当前位置：</STRONG>
    <A href='#'><?= $username ?></A></DIV>

<table cellpadding="0" cellspacing="1" class="table_info">
    <caption>快捷操作</caption>
    <tr>
        <td>
            <input type='button' class="button_style" onclick='history.go(-1);' value='返回至上一页'>
            <input type='button' class="button_style" onclick='history.go(1);' value='前进至下一页'>
            <input type='button' class="button_style" onclick='location.reload();' value='刷新本页'>
        </td>
    </tr>
</table>


<table cellpadding="0" cellspacing="1" class="table_list">
    <caption>
        <?= $user['username'] ?> <?php if ($user['type'] == "1") echo "总";
        echo "代理"; ?> 信息
    </caption>
    <tr>
        <td>股东：<?= $user['username'] ?></td>
        <td>别名：<?= $user['nickName'] ?></td>
        <td>状态：开启</td>
        <td>投注：开启</td>
    </tr>
    <tr>
        <td>真人点数：<?= $user['currentmoney'] ?></td>
        <td>真人占成：0%</td>
        <td>真人洗码：0.0% <?php if ($user['danshuangbian'] == "1") echo "单边"; else echo "双边"; ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">扩展功能：<span class="align_c">
          <input class="button_style" onclick="redirect('/user/agent/add.php?type=agent&id=<?= $uid ?>')" type='button'
                 value='添加下级 代理'/>
		  <input class="button_style" type='button' value='添加会员'
                 onclick="redirect('/user/agent/add.php?type=member&id=<?= $uid ?>')"/>
		  <input class="button_style" type='button' value='<?= $user['username'] ?> 的会员'
                 onclick="redirect('/user/agent/member.php?id=<?= $uid ?>')"/>
		  <input class="button_style" type='button' value='日志'
                 onclick="redirect('/user/log/index.php?s_username=<?= $username ?>')"/>
		  <input class="button_style" type='button' value='子账号'
                 onclick="redirect('/user/agent/submanage.php?id=<?= $uid ?>')"/>
                <?php if ($user['type'] != "0") { ?>
                    <input class="button_style" type='button' value='洗码佣金报表'
                           onclick="redirect('/user/agent/ximareport.php')"/>
                <?php } ?>
        </span></td>
    </tr>
</table>
<table cellpadding="0" cellspacing="1" class="table_list align_c">
    <form method="post" name="myform">
        <caption>
            <?= $user['username'] ?> 下线 代理 账号列表
        </caption>
        <tr align="center">
            <th width="5%"><strong>选中</strong></th>
            <th><strong>用户名</strong></th>
            <th width="10%"><strong>别名</strong></th>
            <th width="10%"><strong>货币类型</th>
            <th width="10%"><strong>真人点数</strong></th>
            <th width="10%">真人占成</th>
            <th width="10%">
                <strong>
                    真人洗码 </strong></th>
            <th width="90">
                <strong>
                    状态 </strong></th>
            <th width="90"><strong>投注</strong></th>
            <th width="90">新建时间</th>
            <th width="412"><strong>管理操作</strong></th>
        </tr>
        <tbody id="userList">
        <?php foreach ($agent as $ag) { ?>
            <tr>
                <td><input type="checkbox" name="userid[]" id="checkbox" value="<?= $ag['id'] ?>"></td>
                <td><a href="/user/agent/index.php?id=<?= $ag['id'] ?>"
                       title="点击查看下级用户::最后登入时间：<?= $ag['lastlogintime'] ?><br>最后登入IP：<?= $ag['ip'] ?> - <?php $ip = $ag['ip'];
                       echo $database->GetIpLocData($ip); ?><br>登入次数：<?= $ag['logintimes'] ?>"><?= $ag['username'] ?></a></td>
                <td><?= $ag['nickName'] ?></td>
                <td>人民币</td>
                <td><?= $ag['currentmoney'] ?></td>
                <td><?= $ag['zhancheng'] ?>%</td>
                <td><?= $ag['xima'] ?>% <?php if ($ag['danshuangbian'] == "1") echo "单边"; else echo "双边"; ?></td>
                <td>
                    <input name="button2" type="button" class="button_style" title="点击锁定该账号" value="开启"/></td>
                <td>
                    <input name="button2" type="button" class="button_style" title="点击锁定该账号投注权限" value="开启"/></td>
                <td><?php echo date("Y-m-d", strtotime($ag['createdate'])) ?></td>
                <td>
                    <a href="/user/agent/edit.php?id=<?= $ag['id'] ?>">修改</a>
                    |
                    <a href="/user/agent/del.php?id=<?= $ag['id'] ?>" onclick="return confirm('确定要删除吗?')">删除</a>
                    |
                    <a href="/user/agent/add.php?type=agent&id=<?= $ag['id'] ?>">添加下级</a>
                    |
                    <a href="/user/agent/editmoney.php?id=<?= $ag['id'] ?>">加减点</a>
                    |
                    <a href="/user/log/index.php?s_username=<?= $ag['username'] ?>">日志</a>
                    |
                    <a href="/user/agent/acledit.php?id=<?= $ag['id'] ?>">权限</a>
                    |
                    <a href="/user/agent/submanage.php?id=<?= $ag['id'] ?>">子账号</a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
</table>
<div class="button_box">
    <input name='button2' type='button' class="button_style" id='chkall' onclick='checkall()' value='全选'>
</div>

<div id="pages">
    <div id="pages">总数：<b><?php echo count($agent); ?></b>&nbsp;
        每页：<b>20</b>
        <a href="/user/agent/index.php?id=<?= $uid ?>&page=1">首页</a><a
            href="/user/agent/index.php?id=<?= $uid ?>&page=1">上一页</a><a
            href="/user/agent/index.php?id=<?= $uid ?>&page=1">下一页</a><a
            href="/user/agent/index.php?id=<?= $uid ?>&page=1">尾页</a>
        页次：<b><font color="red">1</font>/1</b>
        <input type="text" name="page" id="page" size="4"
               onKeyDown="if(event.keyCode==13) {redirect('/user/agent/index.php?id=<?= $uid ?>&page='+this.value); return false;}">
        <input type="button" value="转到" class="button_style"
               onclick="redirect('/user/agent/index.php?id=<?= $uid ?>&page='+$('#page').val())"></div>
</div>
</form>
<table cellpadding="0" cellspacing="1" class="table_form">
    <caption>
        用户搜索
    </caption>
    <tr>
        <td class="align_c">
            用户组：<select name="groupid" id="groupid" size="1">
                <option value="0" selected>不限</option>
                <option value="5">代理</option>
                <option value="6">会员</option>
            </select>
            账号状态：
            <select name='status' id="status">
                <option value='-1'>不限</option>
                <option value='0'>正常</option>
                <option value='1'>锁定</option>
            </select>
            投注状态：
            <select name='livestatus' id="livestatus">
                <option value='-1'>不限</option>
                <option value='0'>正常</option>
                <option value='1'>锁定</option>
            </select>
            账号类型：
            <select name='nametype' id="nametype">
                <option value='0'>用户名</option>
                <option value='1'>别名</option>
                <option value='2'>电话号码</option>
            </select>
            用户名：
            <input type="text" name="username" id="username" value="" size="20" class=""/>
            <input type='button' class="button_style" onclick="go()" value=' 搜索 '>
            &nbsp;
        </td>
    </tr>
</table>
<script type="text/javascript">
    var username = getUrlQuery('username', 3);
    var groupid = parseInt(getUrlQuery('groupid', 3));
    var status = parseInt(getUrlQuery('status', 3));
    var livestatus = parseInt(getUrlQuery('livestatus', 3));
    var nametype = parseInt(getUrlQuery('nametype', 3));
    if (username != null) {
        $("#username").val(username);
    } else {
        username = '不限';
    }
    if (groupid > -1) {
        $("#groupid").setSelectedValue(groupid);
    }
    if (status > -1) {
        $("#status").setSelectedValue(status);
    }
    if (livestatus > -1) {
        $("#livestatus").setSelectedValue(livestatus);
    }
    $("#nametype").setSelectedValue(nametype);
    function go() {
        $("#loading").show();
        var url = '/user/search/index/groupid/' + $("#groupid").val() + '/status/' + $("#status").val() + '/livestatus/' + $("#livestatus").val() + '/username/' + $("#username").val() + '/nametype/' + $("#nametype").val();
        url = "/user/agent/index.php?id=<?=$uid?>&page=1";
        redirect(url);
    }
</script>
</body>
</html>