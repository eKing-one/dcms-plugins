<?
if (isset($user) && isset($set['roller_status']) && !isset($_POST['msg']) && $set['roller_status'] == 1)
{
	$roller_num = (60 * 60 * 24);
	
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `roller` WHERE `id_user` = '" . $user['id'] . "' LIMIT 1"), 0) == 0)
	{
		mysql_query("INSERT INTO `roller` (`id_user`, `time`, `days`) values('$user[id]', '" . ($time - $roller_num) . "', '1')"); 
	}
	
	$roller = mysql_fetch_assoc(mysql_query("SELECT * FROM `roller` WHERE `id_user` = '" . $user['id'] . "' LIMIT 1"));
	
	if (($roller['time'] + $roller_num + 1000) < $ftime && $set['roller_days'] >= $roller['days']){
		mysql_query("UPDATE `roller` SET `time` = '" . ($time - $roller_num) . "', `days` = '1' WHERE `id_user` = '$user[id]' LIMIT 1");
	}
	
	if ($roller['time'] < $time && $_SERVER['PHP_SELF'] != '/user/roller/index.php' && $set['roller_days'] >= $roller['days'])
	{
		header('Location: /user/roller/index.php');
		exit;
	}
}
?>