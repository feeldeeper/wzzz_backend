<?php
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php");
if (isset($_POST['psw']) && $_POST['psw'] != "") {
    $psw = md5($_POST['psw']);
    if ($psw != "") {
        $tab = $_POST['tab'];
        $sql = "update admin set password='$psw',phone='$tab' where id=" . $_POST['id'];
        //echo $sql;exit();
        $database->query($sql);
        echo "<script>alert('已提交!');window.location='admin.php'</script>";
    } else
        echo "<script>alert('密码为空!');history.back(-1);</script>";


}

if (isset($_GET['id']) && $_GET['id'] != "") {
    $id = $_GET['id'];
    $sql = "select * from admin where id=" . $id;
    $row = $database->query($sql)->fetch();
    if ($row) {
        $name = $row['username'];
        $tab = $row['phone'];
    }

}else{
    exit(0);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0036)http://ag.88gobo.net/default/online/ -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title></title>

</head>
<body>

<div><h1 style="padding-left:250px;">修改管理员</h1>
</div>
<div style="padding-left:150px;padding-top:10px;">
    <form action="adminedit.php" method="post">
        <input type='hidden' value="<?= $id ?>" name="id">
        ID: <?= $id ?><br/>
        用户名: <?= $name ?><br/>
        新密码:<input type="text" name="psw" value="" style="width:200px"/><br/>
        控制台号:<input type="text" name="tab" value="<?= $tab ?>" style="width:200px"/><br/>
        <input type="submit" value="修改"><br/><br/>
    </form>

</div>
</body>
</html>