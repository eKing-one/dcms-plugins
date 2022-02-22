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
	
	if (isset($_SERVER["HTTP_USER_AGENT"]) && preg_match('#up-browser|blackberry|windows ce|symbian|palm|nokia#i', $_SERVER["HTTP_USER_AGENT"]))
	$webbrowser=false;
	elseif (isset($_SERVER["HTTP_USER_AGENT"]) && (preg_match('#windows#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#linux#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#bsd#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#x11#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#unix#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#macos#i', $_SERVER["HTTP_USER_AGENT"]) ||preg_match('#macintosh#i', $_SERVER["HTTP_USER_AGENT"])))
	$webbrowser=true;else $webbrowser=false; // определение типа браузера
	if ($webbrowser == true)
	{
		$web_panel = true;
	} else $web_panel = false;
	
	$array_icons_color = array('none', 'blue', 'green', 'pink', 'purple', 'red', 'yellow');
	$array_icons_list = array('home', 'mail', 'journal', 'lenta');
	if (isset($_POST['submited']))
	{
		if ($_POST['mdp']!=md5($user['pass']))$err[] = 'Техническая ошибка';
		$icons_list = htmlspecialchars($_POST['icons_list']);
		if (in_array($icons_list, $array_icons_color))$icons_list = $icons_list; else $icons_list = 'none';
		if (!isset($err))
		{
			mysql_query("UPDATE `user` SET `panel_icons_list` = '$icons_list' WHERE `id` = '$user[id]'");
			$user['panel_icons_list'] = $icons_list;
			$_SESSION['msg_set'] = 1;
			header("Location: ?");
			exit();
			}
	}
	if (isset($_SESSION['msg_set']))
	{
		msg("Настройки успешно сохранены");
		unset($_SESSION['msg_set']);
	}
	echo "<form method='POST' action=''>\n";
		echo "Выберите набор иконок:<br />\n";
		foreach($array_icons_color AS $key => $value_color)
		{
			echo "<input type='radio' id='$value_color' name='icons_list' value='$value_color'".($user['panel_icons_list']==$value_color?" CHECKED":NULL)." /> <label for='$value_color'>\n";
			foreach($array_icons_list AS $key => $value_icon)
			{
				echo "<img src='/style/panel/".$value_icon."_".$value_color."".($web_panel==false?"_16x16":NULL).".png' /> \n";
			}
			echo "</label><br />\n";
		}
		echo "<input type='hidden' name='mdp' value='".md5($user['pass'])."'>\n";
		echo "<input type='submit' name='submited' value='Сохранить' />\n";
	echo "</form>\n";
	echo "<div class='foot'>\n";
		echo "&raquo; <a href='/settings_panel.php'>Назад</a><br />\n";
	echo "</div>\n";
	include_once 'sys/inc/tfoot.php';
?>