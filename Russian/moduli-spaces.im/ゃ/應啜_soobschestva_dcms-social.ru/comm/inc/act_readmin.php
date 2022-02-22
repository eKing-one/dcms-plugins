<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Стать создателем'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();

if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_readmin` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' LIMIT 1"),0)!=0)
{
$vak=mysql_query("SELECT * FROM `comm_readmin` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' LIMIT 1"); // находим заявку
$vak=mysql_fetch_array($vak); // образуем масив
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `activate`='1' AND `invite`='0' LIMIT 1"),0)==1) // если все в порядке
{
if(time()<$vak['time'])
{
if(isset($_GET['yes']))
{
if($mcomms<0)
{
$err[]="Вы имеете уже сообщество";
err();
include_once '../sys/inc/tfoot.php';
exit;
}
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$ank[id]', '$user[nick] принял ваше предложение стать создателем сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url]', '$time')");
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$user[id]', `id_ank` = '".mysql_result(mysql_query("SELECT `id_user` FROM `comm` WHERE `id` = '$comm[id]'"),0)."', `type` = 'access', `time` = '$time', `access` = 'creator'");
mysql_query("UPDATE `comm` SET `id_user` = '$user[id]' WHERE `id` = '$comm[id]'");
mysql_query("DELETE FROM `comm_readmin` WHERE `id_comm` = '$comm[id]'");
mysql_query("UPDATE `comm_users` SET `access` = 'creator' WHERE `id_comm` = '$comm[id]' AND `id_user`='$user[id]'");
mysql_query("UPDATE `comm_users` SET `access` = 'user' WHERE `id_comm` = '$comm[id]' AND `id_user` = '$ank[id]'");
msg("Поздравляем! Вы стали создателем сообщества \"".htmlspecialchars($comm['name'])."\"");
}
else
{
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$ank[id]', '$user[nick] отклонил ваше предложение стать создателем сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");
mysql_query("DELETE FROM `comm_readmin` WHERE `id_comm` = '$comm[id]' LIMIT 1");
$err[]="Заявка отклонена";
}
}
else $err[]="Время вышло";
}
else $err[]="Вы не являетесь учасником даного сообщества";
}
else $err[]="Нет предложений";
err();
}
else{header("Location:/comm");exit;}

?>