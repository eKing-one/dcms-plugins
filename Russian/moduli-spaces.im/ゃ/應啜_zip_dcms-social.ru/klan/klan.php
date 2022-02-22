<?php
########################################
#   Мод кланы для DCMS for VIPTABOR    #
#      Автор: DenSBK ICQ: 830-945	   #
#  Запрещена перепродажа данного мода. #
# Запрещено бесплатное распространение #
#    Все права пренадлежат автору      #
########################################

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$id = intval($_GET['id']);
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '".$id."' LIMIT 1"));

$set['title'] = 'Клан '.$clan['name'];
include_once '../sys/inc/thead.php';
title();
aut();

$us = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_clan` = '$clan[id]' AND `activaty` = '0'"),0);
$level = ''.(5*$us)*$clan['level'].'';


echo "<div class=\"rowup\"><center>\n";
if(is_file(H.'files/klanico/'.$clan['id'].'.jpg')){
echo '<img src="/files/klanico/'.$clan['id'].'.jpg" alt=""/>';
}
echo "<strong>$clan[name]</strong></center></div>\n";
echo "<div class=\"str\">$clan[about]<br />\n";
if(is_file(H.'files/klan/'.$clan['id'].'.jpg')){
echo '<img src="/files/klan/'.$clan['id'].'.jpg" alt=""/>';
}
else
{
echo '<img src="/files/klan/0.png" alt=""/>';
}
echo "</div>\n";
echo "<div class=\"str\">\n";
echo "Рейтинг: <strong>$level</strong> %<br />\n";
echo "Состав: <strong>$us</strong> человек.<br />\n";
echo "Дата создания: <strong>".vremja($clan['time'])."</strong><br />\n";
echo "</div>\n";

echo "<div class=\"str\">\n";
echo "<a href='adm.php?id=$clan[id]'>Администрация клана</a><br/>\n";
echo "<a href='pravila.php?id=$clan[id]'>Правила клана</a><br/>\n";
if(mysql_result(mysql_query("SELECT COUNT(id) FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"),0)==0)echo "<a href='/klan/enter.php?id=$clan[id]'>Вступить в клан</a><br/>\n";
echo "</div>\n";
echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>