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
$set['title']="Банды";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($user) && $user['gang']==0)echo "<div class='foot'><img src='icons/add.png' alt=''> <a href='new_gang.php'>Новая банда</a></div>";
if (isset($user) && $user['gang']!=0)echo "<div class='foot'><img src='icons/users.png' alt=''> <a href='gang.php?id=$user[gang]'>Моя банда</a></div>";
if (isset($user))echo "<div class='foot'><img src='icons/invite.png' alt=''> <a href='my_invite.php'>Мои приглашения   <b>".mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_invite` WHERE `id_kont`='".$user['id']."'"),0)."</b></a></div>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "<div class='mess'>Пока нет ни одной банды</div>";
$q=mysql_query("SELECT * FROM `gangs` ORDER BY `rating` DESC LIMIT $start, $set[p_str]");
while ($gang = mysql_fetch_array($q))
{
if ($num==0){echo "<div class='nav1'>";	$num=1;}elseif ($num==1){echo "<div class='nav2'>";	$num=0;}
echo "<table><td>";
if (is_file(H."gangs/emblem/mini/$gang[id].png")) echo "<img src='/gangs/emblem/mini/$gang[id].png' width='40' height='40' alt='' />";
else echo "<img src='/gangs/icons/emblem_mini.png' width='40' height='40' alt='' />";
echo "</td><td><a href='gang.php?id=$gang[id]'><b>".htmlspecialchars($gang['name'])."</b></a><br/>Состав: <b>".mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0'"),0)." чел.</b><br/>Рейтинг: <b>$gang[rating]%</b>";
echo "</td></table></div>";
}
if ($k_page>1)str("?",$k_page,$page);
include_once H.'sys/inc/tfoot.php';
?>