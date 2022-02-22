<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

/* Бан пользователя */ 
if (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `razdel` = 'guest' AND `id_user` = '$user[id]' AND (`time` > '$time' OR `view` = '0')"), 0) != 0)
{
	header('Location: /ban.php?'.SID);
	exit;
}

// Очищаем уведомления об ответах
if (isset($user))
mysql_query("UPDATE `notification` SET `read` = '1' WHERE `type` = 'guest' AND `id_user` = '$user[id]'");

// Действия с комментариями
include 'inc/admin_act.php';

// Отправка комментариев
if (isset($_POST['msg']) && isset($user))
{
	$msg = $_POST['msg'];
	$mat = antimat($msg);
	if ($mat)$err[] = 'В тексте сообщения обнаружен мат: ' . $mat;

	if (strlen2($msg) > 1024){ $err[] = 'Сообщение слишком длинное'; }
	elseif (strlen2($msg) < 2){ $err[] = 'Короткое сообщение'; }
	elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `guest` WHERE `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0) != 0)
	{
		$err = 'Ваше сообщение повторяет предыдущее';
	}
	elseif(!isset($err))
	{
		// Начисление баллов за активность
		include_once H.'sys/add/user.active.php';

		/*
		==========================
		Уведомления об ответах
		==========================
		*/
		
		if (isset($ank_reply['id']))
		{
			$notifiacation = mysql_fetch_assoc(mysql_query("SELECT * FROM `notification_set` WHERE `id_user` = '" . $ank_reply['id'] . "' LIMIT 1"));
			
			if ($notifiacation['komm'] == 1 && $ank_reply['id'] != $user['id'])
			mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `type`, `time`) VALUES ('$user[id]', '$ank_reply[id]', 'guest', '$time')");
		}

		mysql_query("INSERT INTO `guest` (id_user, time, msg) values('$user[id]', '$time', '" . my_esc($msg) . "')");
		$_SESSION['message'] = 'Сообщение успешно добавлено';
		header ("Location: index.php" . SID);
		exit;
	}
}

// заголовок страницы
$set['title'] = 'Гостевая книга'; 
include_once '../sys/inc/thead.php';
title();
aut();
err();


$k_post = mysql_result(mysql_query("SELECT COUNT(id) FROM `guest`"), 0);
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];

// Форма для комментариев
if (isset($user))
{
	echo '<form method="post" name="message" action="?page=' . $page . REPLY . '">';
	if (is_file(H.'style/themes/' . $set['set_them'] . '/smailes.php'))
	include_once H.'style/themes/' . $set['set_them'] . '/smailes.php';
	else
	echo $tPanel . '<textarea name="msg">' . $insert . '</textarea><br />';
	echo '<input value="Отправить" type="submit" />';
	echo '</form>';
}

echo '<table class="post">';

if ($k_post == 0)
{
	echo '<div class="mess" id="no_object">';
	echo 'Нет сообщений';
	echo '</div>';
}

$q = mysql_query("SELECT * FROM `guest` ORDER BY id DESC LIMIT $start, $set[p_str]");

while ($post = mysql_fetch_assoc($q))
{
	$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));

	// Лесенка
	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">';
	$num++;

	echo user::avatar($ank['id'], 0) . user::nick($ank['id'], 1, 1, 1);

	if (isset($user) && $user['id'] != $ank['id'])
	echo ' <a href="?page=' . $page . '&amp;response=' . $ank['id'] . '">[*]</a> (' . vremja($post['time']) . ')<br />';

	echo output_text($post['msg']) . '<br />';

	if (isset($user) && ($user['level'] > $ank['level'] || $user['level'] != 0 && $user['id'] == $ank['id']) && user_access('guest_delete')) 
	{
		echo '<div class="right">';
		echo '<a href="delete.php?id=' . $post['id'] . '"><img src="/style/icons/delete.gif" alt="*"></a>';
		echo '</div>';
	}
	echo '</div>';
}

echo '</table>';

if ($k_page > 1)str('index.php?', $k_page, $page); // Вывод страниц

echo '<div class="foot">';
echo '<img src="/style/icons/str.gif" alt="*"> <a href="who.php">В гостевой (' . mysql_result(mysql_query("SELECT COUNT(id) FROM `user` WHERE `date_last` > '".(time()-100)."' AND `url` like '/guest/%'"), 0) . ' чел.)</a><br />';
echo '</div>';

// Форма очистки комментов
include 'inc/admin_form.php';

include_once '../sys/inc/tfoot.php';
?>