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
if($user['level']<4 || !isset($user)) die('Deny acccess');

$set['title']='Система контроля ботами - Удаление бота';
include_once '../sys/inc/thead.php';
title();
err();
aut();
if(isset($_GET['id'])) $user_id=intval($_GET['id']);
else die('Нет параметра');

if(isset($_POST['submit'])){mysql_query("DELETE FROM `bot_cron` where `user`='$user_id'");
msg('Удалён');
include_once '../sys/inc/tfoot.php';
}?>
<form method="POST">
	ID бота<br/>
	<input type="text" name="user" value="<? echo $user_id ?>" /><hr/>
	Точно удалить его из системы ботов (сам аккаунт бота не будет удалён)?
	<input type="submit" name="submit" value="Точно, удаляем" />
</form> 

<?
include_once '../sys/inc/tfoot.php';