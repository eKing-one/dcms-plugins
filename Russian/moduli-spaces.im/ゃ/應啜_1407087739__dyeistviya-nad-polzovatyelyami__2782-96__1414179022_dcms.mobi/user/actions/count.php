<?
	$count_actions = mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_user` WHERE `id_user` = '$ank[id]'".($user['id']!=$ank['id'] && $user['level'] < 3?" AND (`type` = '0' OR `type` = '1' AND `id_ank` = '$user[id]')":NULL)), 0);
	echo "($count_actions)";
?>