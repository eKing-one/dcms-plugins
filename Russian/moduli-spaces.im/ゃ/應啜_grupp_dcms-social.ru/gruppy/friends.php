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
if(isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"),0)==1)
{
$s=intval($_GET['s']);
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$s' LIMIT 1"));
include_once 'inc/ban.php';
$set['title']=$gruppy['name'].' - Друзья соо'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if(isset($user) && $user['id']==$gruppy['admid'])
{
if(isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_friends` WHERE `id` = '".intval($_GET['del'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1"),0)==1)
{
mysql_query("DELETE FROM `gruppy_friends` WHERE `id`='".intval($_GET['del'])."' LIMIT 1");
msg('Друг успешно удален');
}
elseif(isset($_POST['friend_add']) && $_POST['friend_add']!=$gruppy['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_POST['friend_add'])."' LIMIT 1"),0)==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_friends` WHERE `id_gruppy`='$gruppy[id]' AND `id_friend`='".intval($_POST['friend_add'])."' LIMIT 1"),0)==0)
{
$add_friend=intval($_POST['friend_add']);
mysql_query("INSERT INTO `gruppy_friends` (`id_gruppy`, `id_friend`, `time`) values ('$gruppy[id]', '$add_friend', '$time')");
msg('Друг соо успешно добавлен');
}
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_friends` WHERE `id_gruppy`='$gruppy[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="msg">';
echo 'Друзей нет';
echo '</div>';
echo '</tr>';
}
$q=mysql_query("SELECT * FROM `gruppy_friends` WHERE `id_gruppy`='$gruppy[id]' ORDER BY `time` ASC LIMIT $start, $set[p_str]");
while ($friends = mysql_fetch_assoc($q))
{
$friend=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$friends[id_friend]' LIMIT 1"));
echo '<tr>';
//echo '<td class="icon14">';
//echo '</td>';
echo '<div class="nav2">';
echo '<img src="img/13od.png" alt="" /> <a href="/gruppy/'.$friend['id'].'">'.$friend['name'].'</a> ('.vremja($friends['time']).')';
if(isset($user) && $user['id']==$gruppy['admid'])echo' [<a href="?s='.$gruppy['id'].'&del='.$friends['id'].'">уд.</a>]<br>';
echo ''.output_text($friend['desc']).'';
echo '</div>';
echo '</tr>';
}
echo '</table>';
if ($k_page>1)str("?s=$gruppy[id]&",$k_page,$page); // Вывод страниц
if(isset($user) && $user['id']==$gruppy['admid'])
{
if(isset($_GET['add']))
{
echo'<form method="post" action="?s='.$gruppy['id'].'">';
echo'<b><u>Введите ID группы</u></b><br/><br/>';
echo'<input type="text" name="friend_add" size="3">';
echo'<input type="submit" value="Добавить">';
echo'</form><br/>';
}
else
{
echo'<div class="nav1"><img src="img/20od.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'&add">Добавить друга</a></div><br/>';
}
}

echo "<div class='foot'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a><br/>';
echo "</div>\n";
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
