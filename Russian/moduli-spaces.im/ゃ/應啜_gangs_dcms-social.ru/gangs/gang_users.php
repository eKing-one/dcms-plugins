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
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==0){header("Location: index.php?".SID);exit;}
$gang = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
$guser= mysql_fetch_array(mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang` = '$gang[id]' AND `id_user` = '".$user['id']."'"));
$set['title']="Состав банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();

if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
if (isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".intval($_GET['del'])."' AND `status`<'$guser[status]'"),0)!=0){
mysql_query("UPDATE `user` SET `gang` = '0' WHERE `id` ='".intval($_GET['del'])."'");
mysql_query("DELETE FROM `gangs_users` WHERE `id_user` ='".intval($_GET['del'])."' AND `id_gang` = '".$gang['id']."'");
$del=get_user($_GET['del']);
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$del['id']."]".$del['nick']."[/url] выгнан(а) из банды.', '".$time."')");
msg("Вы успешно выгнали участника из банды");
}
if (isset($_GET['vb']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".intval($_GET['vb'])."' AND `status`<'$guser[status]'"),0)!=0){
mysql_query("UPDATE `user` SET `gang` = '0' WHERE `id` ='".intval($_GET['vb'])."'");
mysql_query("DELETE FROM `gangs_users` WHERE `id_user` ='".intval($_GET['vb'])."' AND `id_gang` = '".$gang['id']."'");
mysql_query("INSERT INTO `gangs_enemies` (`id_gang`, `id_user`, `time`) values('".$gang['id']."', '".intval($_GET['vb'])."','".$time."')");
msg("Участник успешно успешно добавлен в Враги банды");
}
if (isset($_GET['vg']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".intval($_GET['vg'])."' AND `status`<'$guser[status]'"),0)!=0){
mysql_query("UPDATE `gangs_users` SET `status` = '1' WHERE `id_user` = '".intval($_GET['vg'])."'");
msg("Участник успешно назначен Вице главарем");
}
if (isset($_GET['vgno']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".intval($_GET['vgno'])."' AND `status`<'$guser[status]'"),0)!=0){
mysql_query("UPDATE `gangs_users` SET `status` = '0' WHERE `id_user` = '".intval($_GET['vgno'])."'");
msg("Участник успешно снят с должности Вице главарь");
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "<div class='mess'>Состав этой банды пуст.</div>";
$q=mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank=get_user($post['id_user']);
if ($num==0){echo "<div class='nav1'>";	$num=1;}elseif ($num==1){echo "<div class='nav2'>";	$num=0;}
if ($post['status']==0)$status="";
if ($post['status']==1)$status="Вице главарь";
if ($post['status']==2)$status="Главарь";
echo status($ank['id']) , group($ank['id']);
echo " <a href='/info.php?id=$ank[id]'>$ank[nick]</a> \n";
echo "".medal($ank['id'])." ".online($ank['id'])."<br /><b>".$status."</b>";
if ($post['status']<$guser['status'])echo "<font color='red'>".($post['status']==0?"<a href='?id=".$gang['id']."&vg=".$ank['id']."'>Поставить ВГ</a>":"<a href='?id=".$gang['id']."&vgno=".$ank['id']."'>Снять ВГ</a>")." | </font>";
if ($post['status']<$guser['status']){
echo "<a href='?id=$gang[id]&vb=$ank[id]'>В ВБ </a>| ";
echo "<a href='?id=$gang[id]&del=$ank[id]'>Выгнать </a>";
}
echo "</div>";
}
if ($k_page>1)str("?id=$gang[id]&",$k_page,$page);
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
?>