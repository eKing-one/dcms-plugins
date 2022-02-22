<?
	include_once 'sys/inc/start.php';
	include_once 'sys/inc/compress.php';
	include_once 'sys/inc/sess.php';
	include_once 'sys/inc/home.php';
	include_once 'sys/inc/settings.php';
	include_once 'sys/inc/db_connect.php';
	include_once 'sys/inc/ipua.php';
	include_once 'sys/inc/fnc.php';
	include_once 'sys/inc/user.php';

	only_reg();
	$set['title']='Мои настройки';
	include_once 'sys/inc/thead.php';
	title();
	aut();
	if (isset($_POST['save']))
	{
		$user['panel']=intval($_POST['panel']);
		if (in_array($user['panel'], array(1,2,3)))$user['panel']=$user['panel']; else $user['panel']=1;
		mysql_query("UPDATE `user` SET `panel` = '$user[panel]' WHERE `id` = '$user[id]'");
		$_SESSION['msg_set'] = 1;
		header("Location: ?");
		exit();
	}
	if (isset($_GET['font_size']))
	{
		$user['panel_font_size']=htmlspecialchars($_GET['font_size']);
		if (in_array($user['panel_font_size'], array('small','medium')))$user['panel_font_size']=$user['panel_font_size']; else $user['panel_font_size']='medium';
		mysql_query("UPDATE `user` SET `panel_font_size` = '$user[panel_font_size]' WHERE `id` = '$user[id]'");
		header("Location: ?");
	}
	if (isset($_SESSION['msg_set']))
	{
		msg("Настройки успешно сохранены");
		unset($_SESSION['msg_set']);
	}
	echo "<form method='POST' action=''>\n";
		echo "<input type='radio' id='1' name='panel' value='1'".($user['panel']==1?' CHECKED':NULL)."> <label for='1'>Иконки</label><br />\n";
		echo "<input type='radio' name='panel' id='2' value='2'".($user['panel']==2?' CHECKED':NULL)."> <label for='2'>Слова</label><br />\n";
		echo "<input type='radio' name='panel' id='3' value='3'".($user['panel']==3?' CHECKED':NULL)."> <label for='3'>Буквы</label><br />\n";
		echo "<input type='submit' value='Сохранить' name='save'/>\n";
	echo "</form>\n";
	echo "<div>\n";
		echo "Выберите размер шрифта: \n";
		echo ($user['panel_font_size']=='small'?'<b>':"<a href='?font_size=small'><span>")."Маленький".($user['panel_font_size']=='small'?'</b>':"</span></a>");
		echo " | \n";
		echo ($user['panel_font_size']=='medium'?'<b>':"<a href='?font_size=medium'><span>")."Большой".($user['panel_font_size']=='medium'?'</b>':"</span></a>");
	echo "</div>\n";
	echo "<div class='p_m'>\n";
		echo "<a href='/settings_panel_style.php'>Стиль панелей</a><br />\n";
		echo "<a href='/settings_panel_icons.php'>Выбрать набор иконок</a><br />\n";
	echo "</div>\n";
	echo "<div class='foot'>\n";
		echo "&raquo; <a href='/settings.php'>Назад</a><br />\n";
	echo "</div>\n";
	include_once 'sys/inc/tfoot.php';
?>