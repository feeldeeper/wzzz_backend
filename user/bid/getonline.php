<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
if (isset($_GET['t']) && $_GET['t'] != "") {
    $table = $_GET['t'];
    $today = date("Y-m-d H:i:s", time() - 18000);
    $sql = "select SQL_CACHE count(*) as c from `user` where tableid='$table' and lastlogintime>'$today'";
    $row = $database->query($sql, $database)->fetch();
    if ($row) {
        echo $row['c'];
    } else {
        echo "0";
    }
} else {
    echo "0";
}
?>