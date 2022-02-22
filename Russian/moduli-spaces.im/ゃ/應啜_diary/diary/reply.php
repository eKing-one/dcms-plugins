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
$set['title']='Ответ на комментарий';
include_once '../sys/inc/thead.php';
title();
////////////////////////////////////
if (isset($_GET['ok']))
{
$id_razd=(int)abs((int)$_GET['ok']);

$post = mysql_fetch_array(mysql_query("SELECT * FROM `diary_komm` WHERE `id`='".$id_razd."' LIMIT 1"));
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`=$post[id_user] LIMIT 1"));
$g_adm = mysql_fetch_array(mysql_query("SELECT * FROM `diary_komm` WHERE `id`=$post[id_diary] LIMIT 1"));
$reply = $_POST['reply'];
$reply=mysql_escape_string($reply);

$msg2="[b]$user[nick][/b] ответил на ваш комментарий в  [url=/diary/komm.php?id=$post[id_diary]]дневнике[/url]";
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$msg2', '$time')");


$msg=mysql_escape_string($msg);




mysql_query("UPDATE `diary_komm` SET `reply` = '$reply', `who_reply` = '$user[id]' WHERE `id` = '$post[id]' LIMIT 1");
//mysql_query("UPDATE `diary_komm` SET `reply` = '$reply' WHERE `id` = '$post[id]' LIMIT 1");

header("Location: komm.php?id=$post[id_diary]");
exit;
}

$id=intval($_GET['id']);

$post = mysql_fetch_array(mysql_query("SELECT * FROM `diary_komm` WHERE `id`='".$id."' LIMIT 1"));
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`=$post[id_user] LIMIT 1"));
echo '<div class="page_foot">';
echo '<form method="post" action="reply.php?ok='.$id.'">';

echo 'Ответ: <a href="/smiles.php">[Смайлы]</a><br/><textarea name="reply">'.$ank['nick'].', </textarea><br/>';
echo '<input type="submit" value="Ответить"/>';
echo " <a><br />\n";

echo '</form>';
echo "</div>\n";
echo "<a href='komm.php?id=$post[id_diary]'>Назад</a><br />\n";

include_once '../sys/inc/tfoot.php';

?>
