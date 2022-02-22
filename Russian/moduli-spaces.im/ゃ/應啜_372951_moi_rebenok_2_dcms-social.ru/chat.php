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
$set['title']='Собрание родителей';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if (isset($_POST['msg']))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)
{
$msg=translit($msg);
}
$mat=antimat($msg);
if ($mat)
{
$err='В сообщении обнаружен мат: '.$mat;
}
if (strlen2($msg)>=1025)
{
$err='Сообщение слишком длинное!';
}
if (strlen2($msg)<=2)
{
$err='Короткое сообщение!';
}
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_chat` WHERE `id_user` = '".$user['id']."' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0)
{
$err='Ваше сообщение повторяет предыдущее!';
}
if (!isset($err))
{
mysql_query("INSERT INTO `baby_chat` (id_user, time, msg) values ('".$user['id']."', '".$time."', '".my_esc($msg)."')");
msg('Сообщение успешно добавлено!');
}
}
err();
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_chat`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo 'Нет сообщений!';
echo "</div>";
}
echo "<form method='post' name='message' action='?$passgen'>";
echo "<img src='img/chat.png' alt='Simptom'> <b>Сообщение:</b><br />";
echo "<textarea name='msg'></textarea><br />";
if (isset($user) && $user['set_translit']==1)
{
echo "<label><input type='checkbox' name='translit' value='1' /> Транслит</label><br />";
}
echo "<input value='Написать' type='submit' />";
echo "</form>";
$q=mysql_query("SELECT * FROM `baby_chat` ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$post['id_user']."' LIMIT 1"));
echo "<div class='p_t'>";
echo "<span style='float:right;'>";
echo "<img src='img/time.png' alt='Simptom'> ";
echo "".vremja($post['time'])."";
echo "</span>";
echo "<a href='/info.php?id=".$ank['id']."'>".$ank['nick']."</a> ";
echo "".online($ank['id'])."<br />";
echo "</div>";
echo "<div class='p_m'>";
if ($user['level']>=4)
{
echo "<span style='float:right;'>";
echo "<img src='img/del.png' alt='Simptom'> <a href='chat_del.php?id=".$post['id']."'>Удалить</a>";
echo "</span>";
}
echo "<img src='img/chat.png' alt='Simptom'> ".output_text($post['msg'])."<br />";

echo "</div>";
}
if ($k_page>1)
{
str('?',$k_page,$page);
}
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>