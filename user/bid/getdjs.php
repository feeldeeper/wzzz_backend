<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
if (isset($_GET['t']) && $_GET['t'] != "") {
    $table = $_GET['t'];

    $today = date("Y-m-d", time()) . " 00:00:00";
    $sql = "select SQL_CACHE * from `tablet` where tab_id='$table'";
    $result = $database->query($sql)->fetch();
    if ($row) {
        echo $row['injecttime'];
    } else {
        echo "30";
    }

} else {
    echo "30";
}
?>