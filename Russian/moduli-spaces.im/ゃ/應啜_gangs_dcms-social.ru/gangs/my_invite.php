<?php
/*
Автор: WIZART
e-mail: bi3apt@gmail.com
icq: 617878613
Сайт: WizartWM.RU
*/
include_once '../sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
$set['title']="Мои приглашения в банды";
if (!isset($user))header("location:index.php");
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_GET['yes']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_invite` WHERE `id` = '".intval($_GET['yes'])."'"),0)!=0){
$inv = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs_invite` WHERE `id` = '".intval($_GET['yes'])."' LIMIT 1"));
$invite = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs` WHERE `id` = '".intval($inv['id_gang'])."' LIMIT 1"));
if ($user['gang']!=0)$err[]="Вы уже состоите в одной из банд.";
if ($user['gang']==$invite['id'])$err[]="Вы уже входите в состав этой банды";
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_enemies` WHERE `id_user` = '$user[id]' AND `id_gang` = '$invite[id]' LIMIT 1"),0)!=0)$err[]="Вы не можете вступить в эту банду так как находитесь в списке врагов этой банды.";
if ($invite['cena']>$user['money'] && $invite['closed']==1)$err[]="У вас недостаточно монет для вступления в эту банду.";
if (!isset($err)){
if ($invite['closed']==0){
mysql_query("INSERT INTO `gangs_users` (`id_gang`, `id_user`, `type`, `time`) values('".$invite['id']."', '".$user['id']."', '0', '".$time."')");
mysql_query("UPDATE `user` SET `gang` = '".$gang['id']."' WHERE `id` ='".$user['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$invite['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] вступил(а) в банду.', '".$time."')");
}
if ($invite['closed']==1){
mysql_query("INSERT INTO `gangs_users` (`id_gang`, `id_user`, `time`) values('".$invite['id']."', '".$user['id']."','".$time."')");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$invite['cena'])."', `gang` = '".$invite['id']."' WHERE `id` ='".$user['id']."'");
mysql_query("UPDATE `gangs` SET `money` = '".($invite['money']+$invite['cena'])."' WHERE `id` ='".$invite['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$invite['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] вступил(а) в банду.', '".$time."')");
}
if ($gang['closed']==3){
mysql_query("INSERT INTO `gangs_users` (`id_gang`, `id_user`, `time`,`type`) values('".$invite['id']."', '".$user['id']."', '".$time."','1')");
}
mysql_query("DELETE FROM `gangs_invite` WHERE `id` ='".$invite['id']."' AND `id_gang` = '".$invite['id']."'");
header("location:gang.php?id=$invite[id]");
}
err();
}
if (isset($_GET['no']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_invite` WHERE `id` = '".intval($_GET['no'])."'"),0)!=0){
$invite = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs_invite` WHERE `id` = '".intval($_GET['no'])."' LIMIT 1"));
mysql_query("DELETE FROM `gangs_invite` WHERE `id` ='".$invite['id']."' AND `id_gang` = '".$invite['id_gang']."'");
msg("Вы успешно отклонили предложение вступления в банду");
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_invite` WHERE `id_kont`='".$user['id']."'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "<div class='mess'>У вас нет приглашений.</div>";
$q=mysql_query("SELECT * FROM `gangs_invite` WHERE `id_kont`='".$user['id']."' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
$ank=get_user($post['id_user']);
if ($num==0){echo "<div class='nav1'>";	$num=1;}elseif ($num==1){echo "<div class='nav2'>";	$num=0;}
echo group($ank['id']);
echo " <a href='/info.php?id=$ank[id]'>$ank[nick]</a> \n";
echo "".medal($ank['id'])." ".online($ank['id'])."<br />";
$gang = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs` WHERE `id` = '".intval($post['id_gang'])."' LIMIT 1"));
echo "<b>Приглашает вас в банду: </b><a href='gang.php?id=$gang[id]'>".htmlspecialchars($gang['name'])."</a><br/>";
if ($gang['closed']==1)echo "<font color='red'><b>*Внимание! </b> вступление в эту банду является платным, цена вступления составляет $gang[cena] монет.</font><br/>";
echo "<a href='?yes=$post[id]'>Принять </a> | <a href='?no=$post[id]'>Отклонить</a>";
echo "</div>";
}
if ($k_page>1)str("?id=$gang[id]&",$k_page,$page);
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='index.php'> Банды</a></div>";
include_once H.'sys/inc/tfoot.php';
?>