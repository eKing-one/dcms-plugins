<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '1'"),0)!=0)
{
$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Пригласить'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_GET['add']))
{
$ank2=get_user(intval($_GET['add']));
if($ank2['id']!=0)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$ank2[id]' AND `invite` = '0' AND `activate` = '1'"),0)==0)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_blist` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$ank2[id]'"),0)==0)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$ank2[id]' AND `invite` = '1'"),0)==0)
{
mysql_query("INSERT INTO `comm_users` SET `id_comm` = '$comm[id]', `id_user` = '$ank2[id]', `activate` = '0', `invite` = '1', `invite_user` = '$user[id]', `time` = '$time'");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$ank2[id]', '$user[nick] приглашает Вас вступить в сообщество [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url]. Приглашение действительно на протяжении 3-х часов.', '$time')");
msg("Пользователю отправлено приглашение вступить в сообщество. Оно действительно на протяжении 3-х часов");
}
else $err[]="Пользователю уже выслано приглашение";
}
else $err[]="Пользователь находится в Черном списке сообщества";
}
else $err[]="Пользователь уже являетесь участником сообщества";
}
else $err[]="Пользователь не найден";
}

if(isset($_POST['nick']) && isset($_POST['submited']))
{
$ank2=mysql_query("SELECT * FROM `user` WHERE `nick` = '".my_esc($_POST['nick'])."'");
$ank2=mysql_fetch_array($ank2);

if($ank2['id']!=0)
{
header("Location:?act=invite&id=$comm[id]&add=$ank2[id]");
exit();
}
else $err[]="Пользователь не найден";
}
err();

echo "<form method='post'><input type='text' name='nick' value=''><input type='submit' name='submited' value='Пригласить'></form>";

echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
}
else{header("Location:/comm");exit;}
}
else{header("Location:/comm");exit;}

?>