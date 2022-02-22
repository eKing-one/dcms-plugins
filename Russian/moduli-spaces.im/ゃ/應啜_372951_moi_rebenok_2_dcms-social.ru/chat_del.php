<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Удаление сообщения';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if ($user['level']<=3)
{
header("Location: chat.php?".SID);
exit;
}
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby_chat` WHERE `id` = '".$it."' LIMIT 1"));
}
if (!isset($_GET['id']) || !$post || $post['id']==0)
{
echo "<td class='err'>";
echo "Сообщение отсуствует!";
echo "</div>";
echo "<a href='chat.php'><div class='foot'>";
echo "<img src='img/chat.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
mysql_query("DELETE FROM `baby_chat` WHERE `id` = '".$post['id']."'");
header("Location: chat.php?".SID);
exit;
?>