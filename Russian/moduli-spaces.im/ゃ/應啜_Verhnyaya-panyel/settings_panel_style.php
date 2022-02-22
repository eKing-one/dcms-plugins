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
	if (isset($_GET['act']) && in_array(my_esc($_GET['act']), array('panel_fon','panel_link','panel_newevent','panel_focus_link','panel_focus_fon','panel_border')))
	{
		$act = my_esc($_GET['act']);
		if(isset($_GET['select']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `colors_list` WHERE `id` = '".intval($_GET['select'])."'"),0)!=0)
		{
			$color = mysql_fetch_array(mysql_query("SELECT * FROM `colors_list` WHERE `id` = '".intval($_GET['select'])."'"));
			mysql_query("UPDATE `user` SET `$act` = '$color[color]' WHERE `id` = '$user[id]' LIMIT 1");
			header("Location: ?");
			exit();
		}
		echo "Выберите цвет:\n";
		$query = mysql_query("SELECT * FROM `colors_list`");
		while($post = mysql_fetch_array($query))
		{
			echo "<div style='background-color:#".$post['color'].";padding:2px'>\n";
				echo "<a href='?act=".$act."&select=".$post['id']."' style='display:block; padding-left:10px;'><span><i style='color:black;'>".htmlspecialchars($post['name'])."</i></span></a>\n";
			echo "</div>\n";
		}
		echo "<div class='foot'>\n";
			echo "&raquo; <a href='?'>Назад</a>\n";
		echo "</div>\n";
		include_once 'sys/inc/tfoot.php';
		exit();
	}
	?>
	Настройки панелей
	<table border="0" bgcolor="black" cellpadding="1" cellspacing="1" style="width: 50%;">
	
		<tr bgcolor="white">
			<td bgcolor="white"><a href="?act=panel_fon">Фон:</a></td>
			<td bgcolor="#<? echo $user['panel_fon'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	
		<tr bgcolor="white">
			<td bgcolor="white"><a href="?act=panel_link">Ссылки:</a></td>
			<td bgcolor="#<? echo $user['panel_link'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	
		<tr bgcolor="white">
			<td bgcolor="white"><a href="?act=panel_newevent">Уведомл.:</a></td>
			<td bgcolor="#<? echo $user['panel_newevent'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	
		<tr bgcolor="white">
			<td bgcolor="white"><a href="?act=panel_focus_link">Акт.ссылк.:</a></td>
			<td bgcolor="#<? echo $user['panel_focus_link'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	
		<tr bgcolor="white">
			<td bgcolor="white"><a href="?act=panel_focus_fon">Акт.фон.:</a></td>
			<td bgcolor="#<? echo $user['panel_focus_fon'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	
		<tr bgcolor="white">
			<td bgcolor="white"><a href="?act=panel_border">Разделит.:</a></td>
			<td bgcolor="#<? echo $user['panel_border'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>
	<?
	echo "<div class='foot'>\n";
		echo "&raquo; <a href='settings_panel.php'>Назад</a>\n";
	echo "</div>\n";
	include_once 'sys/inc/tfoot.php';
?>