<?
only_reg("/aut.php");
if (isset($_GET['id'])) {
	$ank = get_user(intval($_GET['id']));
	if (!$ank['id']) {
		$set['title'] .= ' - Ошибка!';
		include_once(H.'sys/inc/thead.php');
		$err[] = 'Пользователь не найден';
		title();
		aut();
		err();
		include_once(H.'sys/inc/tfoot.php');
		exit();
	}
} else {
	$ank = $user;
}
$set['title'] .= ' - Выполнить дейстиве над '.$ank['nick'];
include_once(H.'sys/inc/thead.php');
title();
aut();
if ($ank['id'] == $user['id']) {
	$err[] = "Запрещено так делать :)";
	err();
	include_once(H.'sys/inc/tfoot.php');
	exit();
}
if (isset($_GET['action']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_list` WHERE `id` = '".intval($_GET['action'])."'"), 0)) {
	$action = mysql_fetch_array(mysql_query("SELECT * FROM `actions_list` WHERE `id` = '".intval($_GET['action'])."'"));
	if (isset($_POST['submited'])) {
		if (htmlspecialchars(@$_POST['mdp'])==$mdp) {
			if ($user['balls'] < $action['price'])$err[] = 'У Вас не хватает баллов на это действие';
			if (!isset($err)) {
				if (isset($_POST['type']) && $_POST['type']==1)$type = 1; else $type = 0;
				mysql_query("INSERT INTO `actions_user` SET `id_user` = '$ank[id]', `id_action` = '$action[id]', `id_ank` = '$user[id]', `time` = '$time', `type` = '$type'");
				$aid = mysql_insert_id();
				mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$action['price'])."' WHERE `id` = '$user[id]'");
				mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('$user[id]', '$ank[id]', '$aid', 'actions_action', '$time')");
				header("Location: /info.php?id=$ank[id]");
				$_SESSION['message'] = 'Действие успешно выполнено';
				exit();
			}
		}
	}
	err();
	echo "<div class='main'>\n";
	echo "<img src='/style/actions/action_$action[id].png' /><br />\n";
	echo "<b>".htmlspecialchars(stripslashes($action['name']))."</b>\n";
	echo "</div>\n";
	echo "<div class='main'>\n";
	echo "Стоимость: <span style='color:green'><b>".sklon_text($action['price'], array('балл', 'балла', 'баллов'))."</b></b></span><br />\n";
	echo "</div>\n";
	echo "<div class='main'>\n";
	echo "У вас на счету: <span style='color:green'><b>".sklon_text($user['balls'], array('балл', 'балла', 'баллов'))."</b></span><br/>\n";
	echo "</div>\n";
	echo "<form method='POST' action='' style='border:0px transparent solid;background-color:transparent;padding:0px'>\n";
	echo "<div class='main'>\n";
	echo "Вы действительно хотите совершить это действие над <b>$ank[nick]</b>?<br />\n";
	echo "<label><input type='checkbox' name='type' value='1'> <span style='font-size:12px'>Совершить приватно</span></label><br />\n";
	echo "<input type='hidden' name='mdp' value='$mdp'>\n";
	echo "<input type='submit' name='submited' value='Да, я хочу' />\n";
	echo "</div>\n";
	echo "</form>\n";
	echo "<div class='foot'>\n";
	echo "<img src='/style/icons/str2.gif' alt='Назад' /> <a href='?act=do&id=$ank[id]'>Назад</a>\n";
	echo "</div>\n";
	include_once(H.'sys/inc/tfoot.php');
}
echo "<div class='main'>\n";
echo "Выберите действие над <b>$ank[nick]</b><br />\n";
echo "</div>\n";
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_list`"), 0);
if (!$k_post) {
	echo "<div class='mess'>\n";
	echo "Список действий пуст\n";
	echo "</div>\n";
} else {
	$k_page = k_page($k_post, $set['p_str']);
	$page = page($k_page);
	$start = $set['p_str'] * $page - $set['p_str'];
	echo "<table class='post'>\n";
	$query = mysql_query("SELECT * FROM `actions_list` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
	while ($post = mysql_fetch_array($query)) {
		echo "<tr>\n";
		echo "<td>\n";
		echo "<img src='/style/actions/action_$post[id].png' /><br />\n";
		echo "</td>\n";
		echo "<td class='post main'>\n";
		echo "<a href='?act=do&id=$ank[id]&action=$post[id]'>".htmlspecialchars(stripslashes($post['name']))."</a><br />\n";
		echo "Цена: <b>".sklon_text($post['price'], array('балл', 'балла', 'баллов'))."</b><br />\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	if ($k_page>1) str("?act=do&id=$ank[id]&", $k_page, $page);
}
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='Назад' /> <a href='?id=$ank[id]'>Назад</a>\n";
echo "</div>\n";
include_once(H.'sys/inc/tfoot.php');
?>