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
$set['title']="Банда ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
echo "<div class='nav1'>";
if (is_file(H."gangs/emblem/norm/$gang[id].png")) echo "<img src='/gangs/emblem/norm/$gang[id].png' width='200' height='200' alt='' />";
else echo "<img src='/gangs/icons/emblem_norm.png' width='200' height='200' alt='' />";
echo "</div>";
if ($gang['status']!=NULL)echo "<div class='st_1'></div><div class='st_2'>".output_text($gang['status'])."</div>";
$g=get_user($gang['id_user']);
if ($gang['closed']==0)$closed="Свободное"; else if ($gang['closed']==1)$closed="".$gang['cena']." Монет"; else if ($gang['closed']==2)$closed="По приглашению"; else if ($gang['closed']==3)$closed="По подтверждению";
echo "<div class='nav2'><center><b>Информация о банде</b></center>Главарь: <a href='/info.php?id=$g[id]'><b>$g[nick]</b></a><br/>Направление: <b>".($gang['type']==1?"Добрые ":"Злые")."</b><br/>Вступление: <b>".$closed."</b></div>";
echo "<div class='nav2'><center><b>Актив банды</b></center>Баллы: <b>$gang[balls]</b><br/>Монеты: <b>$gang[money]</b><br/>Рейтинг: <b>$gang[rating]%</b></div>";
if (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".$user['id']."' AND`id_gang` = '".$gang['id']."' LIMIT 1"),0)!=0 && $user['gang']>0)echo "<div class='main'><img src='icons/money.png' alt=''> <a href='gang_rating.php?id=$gang[id]'>Поднять рейтинг банды</a></div>";
if ($user['level']>1 || $guser['status']>0)echo "<div class='main'><img src='icons/set.png' alt=''> <a href='set_gang.php?id=$gang[id]'>Управление бандой</a></div>";
if (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".$user['id']."' AND`id_gang` = '".$gang['id']."' LIMIT 1"),0)==0 && $user['gang']==0)echo "<div class='main'><img src='icons/enter.png' alt=''> <a href='gang_enter.php?id=$gang[id]'>Вступить в банду</a></div>";
else if (isset($user) && $user['gang']!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".$user['id']."' AND `id_gang` = '$gang[id]' LIMIT 1"),0)!=0)echo "<div class='main'><img src='icons/out.png' alt=''> <a href='gang_out.php?id=$gang[id]'>Выйти из банды</a></div>";
if (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".$user['id']."' AND`id_gang` = '".$gang['id']."' LIMIT 1"),0)!=0 && $user['gang']==$gang['id'])echo "<div class='main'><img src='icons/invite.png' alt=''> <a href='gang_invite.php?id=$gang[id]'>Пригласить в банду</a></div>";
echo "<div class='main'><img src='icons/news.png' alt=''> <a href='gang_news.php?id=$gang[id]'>Новости банды   <b>".mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_news` WHERE `id_gang`='".$gang['id']."'"),0)."</b></a></div>";
echo "<div class='main'><img src='icons/users.png' alt=''> <a href='gang_users.php?id=$gang[id]'>Состав банды   <b>".mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0'"),0)." чел.</b></a></div>";
echo "<div class='main'><img src='icons/admin.png' alt=''> <a href='gang_administration.php?id=$gang[id]'>Высший состав <b>".mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0' AND `status`>'0'"),0)." чел.</b></a></div>";
echo "<div class='main'><img src='icons/minichat.png' alt=''> <a href='gang_minichat.php?id=$gang[id]'>Мини-чат банды  <b>".mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_minichat` WHERE `id_gang`='".$gang['id']."'"),0)."</b></a></div>";
if ($guser['status']>0 && $gang['closed']==3)echo "<div class='main'><img src='icons/expect.png' alt=''> <a href='gang_expect.php?id=$gang[id]'>Желают вступить  <b>".mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='1'"),0)." чел.</b></a></div>";
echo "<div class='main'><img src='icons/enemies.png' alt=''> <a href='gang_enemies.php?id=$gang[id]'>Враги банды  <b>".mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_enemies` WHERE `id_gang`='".$gang['id']."'"),0)." чел.</b></a></div>";
include_once H.'sys/inc/tfoot.php';
?>