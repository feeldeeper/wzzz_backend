<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
if (isset($_GET['t']) && $_GET['t'] != "") {
    $table = $_GET['t'];

    $today = date("Y-m-d", time()) . " 00:00:00";
    $sql = "select SQL_CACHE * from `tablet` where tab_id='$table'";
    $row = $database->query($sql, $database)->fetch();
    if ($row) {
        echo $row['telMin'] . "-" . $row['telMax'];
    } else {
        echo "10-20000";
    }
} else {
    echo "10-20000";
}
?>