<?
include_once('../sys/inc/home.php');
include_once(H.'sys/inc/start.php');
include_once(H.'sys/inc/compress.php');
include_once(H.'sys/inc/sess.php');
include_once(H.'sys/inc/settings.php');
include_once(H.'sys/inc/db_connect.php');
include_once(H.'sys/inc/ipua.php');
include_once(H.'sys/inc/fnc.php');
include_once(H.'sys/inc/adm_check.php');
include_once(H.'sys/inc/user.php');
user_access('actions_edit', null, 'index.php?'.SID);
adm_check();
$set['title'] = 'Админка - Действия';
include_once(H.'sys/inc/thead.php');
title();
aut();
if (isset($user))$mdp = md5("Killer$user[pass]");
if (isset($_GET['add'])) {
	if (isset($_POST['submited'])) {
		if (htmlspecialchars(@$_POST['mdp'])==$mdp) {
			$name = $_POST['name'];
			$for_m = $_POST['for_m'];
			$for_w = $_POST['for_w'];
			$price = intval($_POST['price']);
			$file = @$_FILES['file'];
			if ($file['type']!='image/jpeg' && $file['type']!='image/jpg' && $file['type']!='image/gif' && $file['type']!='image/png')$err[] = 'Неверный формат картинки.';
			if (!$name)
				$err[] = 'Введите название действия';
			if (!$for_m)
				$err[] = 'Введите название для мужчин';
			if (!$for_w)
				$err[] = 'Введите название для женщин';
			if (!isset($err)) {
				mysql_query("INSERT INTO `actions_list` SET `name` = '".my_esc($name)."', `for_m` = '".my_esc($for_m)."', `for_w` = '".my_esc($for_w)."', `price` = '$price'");
				$id = mysql_insert_id();
				copy($file['tmp_name'], H."style/actions/action_$id.png");
				header("Location: ?");
				exit();
			}
		}
	}
	err();
	echo "<form method='POST' action='' enctype='multipart/form-data'>\n";
	echo "Название<br />\n";
	echo "<input type='text' name='name' value=''><br />\n";
	echo "Название для мужчин<br />\n";
	echo "<input type='text' name='for_m' value=''><br />\n";
	echo "Название для женщин<br />\n";
	echo "<input type='text' name='for_w' value=''><br />\n";
	echo "Цена<br />\n";
	echo "<input type='text' name='price' value=''><br />\n";
	echo "Изображение действия:<br/>\n";
	echo "<input type='file' name='file' /><br/>\n";
	echo "<input type='hidden' name='mdp' value='$mdp'>\n";
	echo "<input type='submit' name='submited' value='Добавить'>\n";
	echo "</form>\n";
	echo "<div class='foot'>\n";
	echo "<img src='/style/icons/str2.gif' alt='Назад' /> <a href='?'>Назад</a>\n";
	echo "</div>\n";
	include_once(H.'sys/inc/tfoot.php');
}
if (isset($_GET['edit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_list` WHERE `id` = '".intval($_GET['edit'])."'"), 0)) {
	$action = mysql_fetch_array(mysql_query("SELECT * FROM `actions_list` WHERE `id` = '".intval($_GET['edit'])."'"));
	if (isset($_POST['submited'])) {
		if (htmlspecialchars(@$_POST['mdp'])==$mdp) {
			$name = $_POST['name'];
			$for_m = $_POST['for_m'];
			$for_w = $_POST['for_w'];
			$price = intval($_POST['price']);
			if (!$name)
				$err[] = 'Введите название действия';
			if (!$for_m)
				$err[] = 'Введите название для мужчин';
			if (!$for_w)
				$err[] = 'Введите название для женщин';
			if (!isset($err)) {
				mysql_query("UPDATE `actions_list` SET `name` = '".my_esc($name)."', `for_m` = '".my_esc($for_m)."', `for_w` = '".my_esc($for_w)."', `price` = '$price' WHERE `id` = '$action[id]'");
				header("Location: ?");
				exit();
			}
		}
	}
	echo "<form method='POST' action=''>\n";
	echo "Название<br />\n";
	echo "<input type='text' name='name' value='".input_value_text($action['name'])."'><br />\n";
	echo "Название для мужчин<br />\n";
	echo "<input type='text' name='for_m' value='".input_value_text($action['for_m'])."'><br />\n";
	echo "Название для женщин<br />\n";
	echo "<input type='text' name='for_w' value='".input_value_text($action['for_w'])."'><br />\n";
	echo "Цена<br />\n";
	echo "<input type='text' name='price' value='".input_value_text($action['price'])."'><br />\n";
	echo "<input type='hidden' name='mdp' value='$mdp'>\n";
	echo "<input type='submit' name='submited' value='Сохранить'>\n";
	echo "</form>\n";
	echo "<div class='foot'>\n";
	echo "<img src='/style/icons/str2.gif' alt='Назад' /> <a href='?'>Назад</a>\n";
	echo "</div>\n";
	include_once(H.'sys/inc/tfoot.php');
}
if (isset($_GET['image']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_list` WHERE `id` = '".intval($_GET['image'])."'"), 0)) {
	$action = mysql_fetch_array(mysql_query("SELECT * FROM `actions_list` WHERE `id` = '".intval($_GET['image'])."'"));
	if (isset($_POST['submited'])) {
		if (htmlspecialchars(@$_POST['mdp'])==$mdp) {
			$file = $_FILES['file'];
			if (!in_array($file['type'], array('image/jpeg', 'image/jpg', 'image/gif', 'image/png')))
				$err[] = 'Неверный формат картинки.';
			if (!isset($err)) {
				unlink(H."style/actions/action_$action[id].png");
				copy($file['tmp_name'], H."style/actions/action_$action[id].png");
				header("Location: ?");
				exit();
			}
		}
	}
	err();
	echo "<form method='POST' action='' enctype='multipart/form-data'>\n";
	echo "Изображение действия:<br/>\n";
	echo "<input type='file' name='file' /><br/>\n";
	echo "<input type='hidden' name='mdp' value='$mdp'>\n";
	echo "<input type='submit' name='submited' value='Сохранить'>\n";
	echo "</form>\n";
	echo "<div class='foot'>\n";
	echo "<img src='/style/icons/str2.gif' alt='Назад' /> <a href='?'>Назад</a>\n";
	echo "</div>\n";
	include_once(H.'sys/inc/tfoot.php');
}
if (isset($_GET['delete']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_list` WHERE `id` = '".intval($_GET['delete'])."'"), 0)) {
	$action = mysql_fetch_array(mysql_query("SELECT * FROM `actions_list` WHERE `id` = '".intval($_GET['delete'])."'"));
	if (isset($_POST['submited'])) {
		if (htmlspecialchars(@$_POST['mdp'])==$mdp) {
			mysql_query("DELETE FROM `actions_list` WHERE `id` = '$action[id]'");
			mysql_query("DELETE FROM `actions_user` WHERE `id_action` = '$action[id]'");
			unlink(H."style/actions/action_$action[id].png");
			header("Location: ?");
			exit();
		}
	}
	echo "<form method='POST' action=''>\n";
	echo "Вы действительно хотите удалить действие?<br />\n";
	echo "<input type='hidden' name='mdp' value='$mdp'>\n";
	echo "<input type='submit' name='submited' value='Да, удалить'>\n";
	echo "</form>\n";
	echo "<div class='foot'>\n";
	echo "<img src='/style/icons/str2.gif' alt='Назад' /> <a href='?'>Назад</a>\n";
	echo "</div>\n";
	include_once(H.'sys/inc/tfoot.php');
}
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_list`"), 0);
echo "<table class='post'>\n";
if (!$k_post) {
	echo "<div class='mess'>\n";
	echo "Список действий пуст\n";
	echo "</div>\n";
} else {
	$k_page = k_page($k_post, $set['p_str']);
	$page = page($k_page);
	$start = $set['p_str'] * $page - $set['p_str'];
	$query = mysql_query("SELECT * FROM `actions_list` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
	while ($post = mysql_fetch_array($query)) {
		echo "<tr>\n";
		echo "<td>\n";
		echo "<a href='?image=$post[id]'><img src='/style/actions/action_$post[id].png' /></a><br />\n";
		echo "</td>\n";
		echo "<td class='post main'>\n";
		echo "<span style='float: right;'><a href='?edit=$post[id]'>[ред]</a> <a href='?delete=$post[id]'>[удал]</a></span>\n";
		echo htmlspecialchars(stripslashes($post['name']))."<br />\n";
		echo "М: ".htmlspecialchars(stripslashes($post['for_m']))."<br />\n";
		echo "Ж: ".htmlspecialchars(stripslashes($post['for_w']))."<br />\n";
		echo "Цена: <b>".sklon_text($post['price'], array('балл', 'балла', 'баллов'))."</b><br />\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	if ($k_page>1)str("?", $k_page, $page);
}
echo "<div class='foot'>\n";
echo "<a href='?add'>Добавить действие</a>\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='Назад' /> <a href='index.php'>В админку</a>\n";
echo "</div>\n";
include_once(H.'sys/inc/tfoot.php');
?>