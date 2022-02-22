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
$set['title']='Виджеты'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

err();


echo "<div class='act'>";

echo "<a class='act_noactive' href='/settings/'>Настройки</a>";

echo'<a class="act_active">Виджеты</a>';
 echo"</div>";
echo'<div class="hide">Виджеты главной страницы</div>';

////   Показ снега ------------------
if(isset($_GET['post_sneg'])){
	if(isset($_POST['sneg'])){
		mysql_query("UPDATE `user` SET `post_sneg` = '".intval($_POST['sneg'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Включить снег по сайту<br />
	<select name="sneg">
	<option value="0"'.($user['post_sneg']==0?' selected="selected"':null).'>Показывать</option>
	<option value="1"'.($user['post_sneg']==1?' selected="selected"':null).'>Не показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_sneg">Включить снег по сайту</a></div>';








////   Показ 3 последних комментария форума  ------------------
if(isset($_GET['post_forum'])){
	if(isset($_POST['forum'])){
		mysql_query("UPDATE `user` SET `post_forum` = '".intval($_POST['forum'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать последние темы форума<br />
	<select name="forum">
	<option value="0"'.($user['post_forum']==0?' selected="selected"':null).'>Не показывать</option>
	<option value="1"'.($user['post_forum']==1?' selected="selected"':null).'>Показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_forum">Показывать последние темы форума</a></div>';




////   Показ 3 последних комментария беседке  ------------------
if(isset($_GET['post_chat'])){
	if(isset($_POST['chat'])){
		mysql_query("UPDATE `user` SET `post_chat` = '".intval($_POST['chat'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать последние комментарии беседки<br />
	<select name="chat">
	<option value="0"'.($user['post_chat']==0?' selected="selected"':null).'>Не показывать</option>
	<option value="1"'.($user['post_chat']==1?' selected="selected"':null).'>Показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_chat">Показывать последние комментарии  беседки</a></div>';



////   Показ 3 последних файлов зоны  ------------------
if(isset($_GET['post_obmen'])){
	if(isset($_POST['obmen'])){
		mysql_query("UPDATE `user` SET `post_obmen` = '".intval($_POST['obmen'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать последние файлы зоны обмена<br />
	<select name="obmen">
	<option value="0"'.($user['post_obmen']==0?' selected="selected"':null).'>Не показывать</option>
	<option value="1"'.($user['post_obmen']==1?' selected="selected"':null).'>Показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_obmen">Показывать последние файлы зоны обмена</a></div>';


////   Показ 3 последних записей дневника ------------------
if(isset($_GET['post_notes'])){
	if(isset($_POST['notes'])){
		mysql_query("UPDATE `user` SET `post_notes` = '".intval($_POST['notes'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать последние записи дневника<br />
	<select name="notes">
	<option value="0"'.($user['post_notes']==0?' selected="selected"':null).'>Не показывать</option>
	<option value="1"'.($user['post_notes']==1?' selected="selected"':null).'>Показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_notes">Показывать последние записи дневника</a></div>';


////   Показ  лидеров на главной ------------------
if(isset($_GET['post_liders'])){
	if(isset($_POST['liders'])){
		mysql_query("UPDATE `user` SET `post_liders` = '".intval($_POST['liders'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать лидеров  на главной  странице<br />
	<select name="liders">
	<option value="0"'.($user['post_liders']==0?' selected="selected"':null).'>Показывать</option>
	<option value="1"'.($user['post_liders']==1?' selected="selected"':null).'>Не показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_liders">Показывать лидеров  на главной  странице</a></div>';






echo'<div class="hide">Виджеты личной страницы</div>';


////   Показ  друзей ------------------
if(isset($_GET['post_frends'])){
	if(isset($_POST['frends'])){
		mysql_query("UPDATE `user` SET `post_frends` = '".intval($_POST['frends'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать друзей которые в сети<br />
	<select name="frends">
	<option value="0"'.($user['post_frends']==0?' selected="selected"':null).'>Не показывать</option>
	<option value="1"'.($user['post_frends']==1?' selected="selected"':null).'>Показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_frends">Показывать друзей которые в сети</a></div>';


////   Показ  наград ------------------
if(isset($_GET['post_rank'])){
	if(isset($_POST['rank'])){
		mysql_query("UPDATE `user` SET `post_rank` = '".intval($_POST['rank'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать нагрыды на личной странице<br />
	<select name="rank">
	<option value="0"'.($user['post_rank']==0?' selected="selected"':null).'>Не показывать</option>
	<option value="1"'.($user['post_rank']==1?' selected="selected"':null).'>Показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_rank">Показывать нагрыды на личной странице</a></div>';


////   Показ  фото пользователей------------------
if(isset($_GET['post_foto'])){
	if(isset($_POST['foto'])){
		mysql_query("UPDATE `user` SET `post_foto` = '".intval($_POST['foto'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать последние фото на личной странице<br />
	<select name="foto">
	<option value="0"'.($user['post_foto']==0?' selected="selected"':null).'>Не показывать</option>
	<option value="1"'.($user['post_foto']==1?' selected="selected"':null).'>Показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_foto">Показывать последние фото на личной странице</a></div>';




if (user_access('adm_panel_show')){
echo'<div class="hide">Виджеты для админ соства</div>';

////   Показ  админ панели ------------------
if(isset($_GET['post_admin'])){
	if(isset($_POST['admin'])){
		mysql_query("UPDATE `user` SET `post_admin` = '".intval($_POST['admin'])."' WHERE `id` = '$user[id]'");
		header('Location: index.php');
		$_SESSION['message'] = 'Настройки изменены';
	}

	echo '<div class="block"><form method="post">
	Показывать админ панель.<br />
	<select name="admin">
	<option value="0"'.($user['post_admin']==0?' selected="selected"':null).'>Показывать</option>
	<option value="1"'.($user['post_admin']==1?' selected="selected"':null).'>Не показывать</option>
	</select>
	<br /><input type="submit" value="Сохранить">
	</form></div>';
	echo '<div class="hide"><a href="index.php">Настройки виджета</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}
echo '<div class="block"><a href="?post_admin">Показывать админ панель.</a></div>';

}


include_once '../sys/inc/tfoot.php';
?>