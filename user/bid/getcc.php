<?php
header("Content-type: text/html; charset=utf-8");
include("../inc/conn.php");
if (isset($_GET['t']) && $_GET['t'] != "") {
    $table = $_GET['t'];

    $today = date("Y-m-d", time()) . " 00:00:00";
    $sql = "select SQL_CACHE * from `round` where createtime>='$today' and tab_id='$table' order by rid desc limit 1";
    $row = $database->query($sql)->fetch();

    if ($row) {
        echo $row['gameBoot'] . "-" . (intval($row['roundNum']) + 1);
    } else {
        echo "0-0";
    }

} else {
    echo "0-0";
}
?>