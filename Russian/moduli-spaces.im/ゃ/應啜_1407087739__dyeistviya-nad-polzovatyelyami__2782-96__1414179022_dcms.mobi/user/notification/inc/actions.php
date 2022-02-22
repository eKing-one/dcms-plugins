<?
# Новое дейтвие
if ($type == 'actions_action') {
	echo "<span style='float:right'><a href='?komm&amp;del=$post[id]&amp;page=$page'><img src='/style/icons/delete.gif' alt='*' /></a></span>";
	mysql_query("UPDATE `notification` SET `read` = '1' WHERE `id` = '$post[id]'");
	$user_action = mysql_fetch_array(mysql_query("SELECT * FROM `actions_user` WHERE `id` = '$post[id_object]' AND `id_user` = '$user[id]'"));
	if ($user_action['id']) {
		$action = mysql_fetch_array(mysql_query("SELECT * FROM `actions_list` WHERE `id` = '$user_action[id_action]'"));
		if ($action['id']) {
			echo status($avtor['id']).group($avtor['id'])." <a href='/info.php?id=$avtor[id]'>$avtor[nick]</a> ".medal($avtor['id']).online($avtor['id'])." ".htmlspecialchars(stripslashes($avtor['pol'] == 1 ? $action['for_m'] : $action['for_w'])).($post['type'] == 1? " <span style='color:red'>[приватное]</span>" : NULL);
			echo " $s1 ".vremja($post['time'])." $s2";
		} else {
			echo "Действие удалено =(  $s1 ".vremja($post['time'])." $s2";
			mysql_query("DELETE FROM `notification` WHERE `id` = '$post[id]'");
		}
	} else {
		echo "Действие удалено =(  $s1 ".vremja($post['time'])." $s2";
		mysql_query("DELETE FROM `notification` WHERE `id` = '$post[id]'");
	}
}
?>