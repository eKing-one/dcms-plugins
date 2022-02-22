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

$set['title']='Система контроля ботами - Добавление бота';
include_once '../sys/inc/thead.php';
title();
err();
aut();


if(isset($_POST['submit'])){
$user_id=intval($_POST['user']);
$time_start=intval($_POST['time_start']);
$time_end=intval($_POST['time_end']);
mysql_query("INSERT INTO `bot_cron` (`user`, `time_start`, `time_end`) values('".$user_id."', '".$time_start."', '$time_end')",$db);
msg('Бот успешно добавлен');
} else {
?><div class='err'>Не приняты параметры $_POST для обработки запроса, пожалуйста, повторите запрос вернувшись на предыдущую страницу</div><?
include_once '../sys/inc/tfoot.php';
}

include_once '../sys/inc/tfoot.php';