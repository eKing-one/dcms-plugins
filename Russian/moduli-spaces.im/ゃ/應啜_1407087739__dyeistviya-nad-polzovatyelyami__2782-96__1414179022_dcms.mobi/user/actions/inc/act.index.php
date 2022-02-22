<?
if (isset($_GET['id'])) 	{
	$ank = get_user(intval($_GET['id']));
	if (!$ank['id']) {
		$set['title'] .= ' - Ошибка!';
		include_once '../sys/inc/thead.php';
		$err[] = 'Пользователь не найден.';
		title();
		aut();
		err();
		include_once(H.'sys/inc/tfoot.php');
	}
} else {
	only_reg("/aut.php");
	$ank = $user;
}
$set['title'] .= ' c '.$ank['nick'];
include_once(H.'sys/inc/thead.php');
title();
aut();
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_user` WHERE `id_user` = '$ank[id]'".(isset($user) && ($user['id'] == $ank['id'] || $user['level'] > 3) ? NULL : " AND (`type` = '0' OR `type` = '1' AND `id_ank` = '$user[id]')").""), 0);
if (!$k_post) {
	echo "<div class='mess'>\n";
	echo "Список действий пуст\n";
	echo "</div>\n";
} else {
	$k_page = k_page($k_post, $set['p_str']);
	$page = page($k_page);
	$start = $set['p_str'] * $page - $set['p_str'];
	echo "<table class='post'>\n";
	$query = mysql_query("SELECT * FROM `actions_user` WHERE `id_user` = '$ank[id]'".($user['id']!=$ank['id'] && $user['level'] < 3?" AND (`type` = '0' OR `type` = '1' AND `id_ank` = '$user[id]')":NULL)." ORDER BY `time` DESC LIMIT $start, $set[p_str]");
	while ($post = mysql_fetch_array($query)) {
		$ank2 = get_user($post['id_ank']);
		$action = mysql_fetch_array(mysql_query("SELECT * FROM `actions_list` WHERE `id` = '$post[id_action]'"));
		echo "<tr>\n";
		echo "<td>\n";
		echo "<img src='/style/actions/action_$action[id].png' />\n";
		echo "</td>\n";
		echo "<td class='post main'>\n";
		echo group($ank['id'])."<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> ".online($ank2['id']);
		echo " (".vremja($post['time']).")<br />\n";
		echo htmlspecialchars(stripslashes($ank2['pol'] == 1 ? $action['for_m'] : $action['for_w'])).($post['type'] == 1? " <span style='color:red'>[приватное]</span>" : NULL)."<br />\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	if ($k_page > 1) str("?id=$ank[id]&", $k_page, $page);
}
if (isset($user) && $user['id'] != $ank['id']) {
	echo "<div class='foot'>\n";
	echo "<img src='/style/icons/action_user.png' alt='Выполнить действие' /> <a href='?act=do&id=$ank[id]'>Выполнить действие</a>\n";
	echo "</div>\n";
}
echo "<div class='foot'>\n";
	echo "<img src='/style/icons/str2.gif' alt='Назад' /> <a href='/info.php?id=$ank[id]'>Назад</a>\n";
echo "</div>\n";
include_once(H.'sys/inc/tfoot.php');
?>