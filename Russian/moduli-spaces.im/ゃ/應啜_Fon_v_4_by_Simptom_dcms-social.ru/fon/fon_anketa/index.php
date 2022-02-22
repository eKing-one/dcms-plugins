<?php
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';
$set['title']='Фон личной анкеты';
Error_Reporting(0);
include_once '../../sys/inc/thead.php';
title();
$q = mysql_query("SELECT * FROM `user` WHERE `id` = '$user[id]' LIMIT 1");
$user = mysql_fetch_assoc($q);
$fon_anketa = mysql_fetch_array(mysql_query("SELECT * FROM `fon_anketa` WHERE `user_id` = '".$user['id']."'"));
$act=htmlspecialchars(trim($_GET['act']));
switch ($act) {
case 'fon_by_Simptom':
echo 'У вас: '.$user['balls'].' балов.<br/>';
echo 'После покупки фона все обитатели, посетившие вашу анкету увидят ее в новом оформлении. Фон на вашей анкете будет в течении недели. После он исчезнет.<br/><hr>';
if (is_file(H."fon/img_anketa/$user[id].gif") || is_file(H."fon/img_anketa/$user[id].jpg") || is_file(H."fon/img_anketa/$user[id].png"))
{
echo '<div class = "foot">';
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'Вы не можете выбрать фон! Для начала удалите фон, который вы выгружали!<br />';
echo '<img src="/fon/ico/del.png" alt=""/> ';
echo '<a href="/fon/fon_anketa.php?act=delete">Удалить фон</a><br />';
echo '</div>';
}else{
$fon_anketa = mysql_fetch_array(mysql_query("SELECT * FROM `fon_anketa` WHERE `user_id` = '".$user['id']."'"));
if ($user['fon_anketa_vibor']==0)
{
if ($fon_anketa['fon_id']>=1)	
{
echo '<div class = "aut">';
echo '<img src="/fon/ico/prod.png" alt=""/> ';
echo '<a href="?act=ok&amp;select=0">Продать фон за 50 балов</a><br />';
echo '</div>';
}else{
echo '<div class = "aut">';
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'Вы не можете продать фон, так как его у вас нету!<br />';
echo '</div>';
}
}else{
echo '<div class = "aut">';
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'Вам запрещено продавать фон!<br />';
echo '</div>';
}
if ($user['fon_anketa_vibor']==1)
{
echo '<div class = "aut">';
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'Вам запрещено покупать фон!<br />';
echo '</div>';
}else{
echo '<img src="/fon/img/1.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №1 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=1">Купить фон</a><br /><hr>';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br /><hr>';
}

echo '<img src="/fon/img/2.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №2 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=2">Купить фон</a><br /><hr>';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br /><hr>';
}
echo '<img src="/fon/img/3.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №3 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=3">Купить фон</a><br /><hr>';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br /><hr>';
}
echo '<img src="/fon/img/4.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №4 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=4">Купить фон</a><br /><hr>';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br /><hr>';
}
echo '<img src="/fon/img/5.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №5 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=5">Купить фон</a><br /><hr>';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br /><hr>';
}
echo '<img src="/fon/img/6.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №6 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=6">Купить фон</a><br /><hr>';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br /><hr>';
}
echo '<img src="/fon/img/7.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №7 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=7">Купить фон</a><br /><hr>';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br /><hr>';
}
echo '<img src="/fon/img/8.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №8 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=8">Купить фон</a><br /><hr>';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br /><hr>';
}
echo '<img src="/fon/img/9.jpg" alt="" width="80" height="80" /><br />';
echo 'Фон №9 (150 балов)<br/>';
if ($user['balls'] >= 150)
{
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo '<a href="?act=ok&amp;select=9">Купить фон</a><br />';
}else{
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для покупки фона!<br />';
}
}
}
echo "<div class=\"foot\">\n";
echo '<img src="/fon/ico/back.gif" alt=""/> ';
echo "<a href='/fon/fon_anketa/'>Назад</a><br />\n";
echo "</div>\n";
break;
case 'ok':
$select = abs(intval($_GET['select']));
if ($select == 0) $price = $user['balls'] + 50;
if ($select == 1) $price = $user['balls'] - 150;
if ($select == 2) $price = $user['balls'] - 150;
if ($select == 3) $price = $user['balls'] - 150;
if ($select == 4) $price = $user['balls'] - 150;
if ($select == 5) $price = $user['balls'] - 150;
if ($select == 6) $price = $user['balls'] - 150;
if ($select == 7) $price = $user['balls'] - 150;
if ($select == 8) $price = $user['balls'] - 150;
if ($select == 9) $price = $user['balls'] - 150;
if ($select >= 0 AND $select <= 9) {
if ($price >= 0) {
echo 'Ваш фон успешно изменен.<br/>';
echo '<img src="/fon/ico/go.gif" alt=""/> ';
echo '<a href="/info.php">Перейти к себе</a>';
mysql_query("UPDATE `fon_anketa` SET `fon_id` = '$select', `time` = '$realtime' WHERE `user_id` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `balls` = '$price' WHERE `id` = '".$user['id']."'");
}else{
echo 'Ошибка!';
}
}else{
echo 'Ошибка!';
}
break;

default:
echo 'Хотите что бы ваша анкета отличалась от остальных красивым фоном?<br/>';
echo '<img src="/fon/ico/da.gif" alt=""/> ';
echo '<a href="?act=fon_by_Simptom">Хочу</a>';
echo "<div class=\"foot\">\n";
echo '<img src="/fon/ico/back.gif" alt=""/> ';
echo "<a href='/fon/'>Назад</a><br />\n";
echo "</div>\n";
if (!isset($fon_anketa['user_id'])) mysql_query("INSERT INTO `fon_anketa` SET `user_id` = '".$user['id']."'");
break;



}
include_once '../../sys/inc/tfoot.php';
?>