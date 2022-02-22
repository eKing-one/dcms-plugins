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
$gang = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==0 && isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".$user['id']."' AND`id_gang` = '".$gang['id']."' LIMIT 1"),0)!=0 && $user['gang']>0){header("Location: index.php?".SID);exit;}
$set['title']="Поднять рейтинг банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
if (isset($_POST['ok'])){
$rating=intval($_POST['rating']);
$money=$rating*10;
if ($user['money']<$money)$err[]="Недостаточно монет";
if (!isset($err)){
mysql_query("UPDATE `gangs` SET `rating` = '".($gang['rating']+$rating)."' WHERE `id` = '$gang[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$money)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['nick']."]".$user['nick']."[/url] поднял рейтинг банды на  $rating %.', '".$time."')");
msg("Рейтинг банды успешно поднят на $rating %");
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
err();
}
echo "<form method='post' action='?id=$gang[id]&passgen'><b>Стоимость поднятия рейтинга банды  составляет: <font color='green'>1% рейтинга = 10 монет.</font></b><br/>
<b>На сколько поднимаем рейтинг?</b><br/><select name='rating' style='width:97%'>
<option value='1'>1%</option>
<option value='2'>2%</option>
<option value='3'>3%</option>
<option value='4'>4%</option>
<option value='5'>5%</option>
<option value='6'>6%</option>
<option value='7'>7%</option>
<option value='8'>8%</option>
<option value='9'>9%</option>
<option value='10'>10%</option>
<option value='11'>11%</option>
<option value='12'>12%</option>

<option value='13'>13%</option>
<option value='14'>14%</option>
<option value='15'>15%</option>
</select><br/>
<input type='submit' name='ok' value='Поднять рейтинг банды' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
?>