<?php 
header("Content-type: text/html; charset=utf-8");
include("inc/conn.php"); 

 
	$text='<table border="1" cellpadding="0" cellspacing="0"> <tr><th>ID</th><th>用户名</th><th>充值金额</th><th>剩余金额</th><th>状态</th><th></th><th>操作</th></tr>';
	$sql="select * from `user`";
	$result = $database->query($sql)->fetchAll();
	if($result) {
		foreach ($result as $row) {
			if ($row['tableid'] == "0")
				$table = "离台";
			else {
				$table = $row['tableid'] . "号台";
			}
			$text = $text . "<tr style='color:red;'><td>" . $row['id'] . "</td><td>" . $row['username'] . "</td><td>" . $row['money'] . "</td><td>" . $row['currentmoney'] . "</td><td>" . $table . "</td><td><a href='?action=del&id=" . $row['id'] . "'>删除</a></td><td><a href='useredit.php?id=" . $row['id'] . "'>修改充值</a></td></td></tr>";
		}
	}
	 
	$text.=' </table>'; 
	echo $text;
	
	
 
?>