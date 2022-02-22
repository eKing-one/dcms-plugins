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

$set['title']='Система контроля ботами';
include_once '../sys/inc/thead.php';
title();
err();
aut();
if(isset($_GET['user'])) $user_id=$_GET['user'];
else $user_id='';

if(mysql_result(mysql_query("SELECT COUNT(*) FROM `bot_cron`"), 0)!=0){
$bot_query=mysql_query("SELECT * from `bot_cron`");
while($bot=mysql_fetch_array($bot_query)){
?><a href="/info.php?id=<?=$bot['user']?>">Анкета ID <?=$bot['user']?></a> [<a href="delete.php?id=<?=$bot['user']?>">X</a>]<br/><?
                                           }
                                                                       }

?>

<form method="POST" action="add.php">
	ID бота<br/>
	<input type="text" name="user" value="<? echo $user_id ?>" /><hr/>
	Во сколько часов примерно заходить (от 1 до 23 часов) ?<br/>
	<input type="text" name="time_start" value="" /><hr/>
	Во сколько часов примерно выходить (от 1 до 23 часов) ?<br/>
	<input type="text" name="time_end" value="" /><hr/>
	<input type="submit" name="submit" value="Добавить" />
</form> 

<?
include_once '../sys/inc/tfoot.php';