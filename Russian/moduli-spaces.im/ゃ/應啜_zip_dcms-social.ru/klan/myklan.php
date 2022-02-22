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

$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' AND `activaty` = '0' LIMIT 1"));
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$us[id_clan]' LIMIT 1"));
$set['title'] = 'Мой клан';
include_once '../sys/inc/thead.php';
title();
aut();

if (mysql_result(mysql_query("SELECT COUNT(id) FROM `clan_user` where `id_user` = '$user[id]' AND `activaty` = '0' LIMIT 1"),0)==0){
echo "<div class=\"str\">\n";
echo "Вы не состоите в клане\n";
echo "</div>\n";
}
else
{
$count = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_clan` = '$clan[id]' AND `activaty` = '0'"),0);
$level = ($clan['level']*5)*$count;


echo "<div class=\"rowup\"><center>\n";
if(is_file(H.'files/klanico/'.$clan['id'].'.jpg')){
echo '<img src="/files/klanico/'.$clan['id'].'.jpg" alt=""/>';
}
echo "<strong>$clan[name]</strong></center></div>\n";

echo "<div class=\"str\">$clan[about]<br />\n";
if(is_file(H.'files/klan/'.$clan['id'].'.jpg')){
echo '<img src="/files/klan/'.$clan['id'].'.jpg" alt=""/><br/>';
}
else
{
echo '<img src="/files/klan/0.png" alt=""/><br/>';
}
$c = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_clan` = '$clan[id]' AND `activaty` = '1'"),0);
if ($us['level']==2) 
{
echo "*<a href='adminka.php?id=$clan[id]'>Админ панель клана</a><br/>\n";
echo "* <a href='adm_chat.php'>Админ чат клана</a><br/>\n";
if ($c>0)echo "*<a href='adminka.php?id=$clan[id]&amp;act=activate'>Активация участников</a> ($c)<br/>\n";
}
if ($us['level']==1){
echo "* <a href='moderka.php?id=$clan[id]'>Модер панель клана</a><br/>\n";
echo "* <a href='adm_chat.php'>Админ чат клана</a><br/>\n";
if ($c>0)echo "*<a href='moderka.php?id=$clan[id]&amp;act=activate'>Активация участников</a> ($c)<br/>\n";
}
echo "</div>\n";
echo "<div class=\"str\">\n";
echo "Рейтинг: <strong>$level</strong> %<br />\n";
echo "Уровень: <strong>$clan[level]</strong> \n";
echo "<a href='/klan/reyt.php'>поднять</a><br/>\n";
echo "В банке: <strong>$clan[bank]</strong> баллов.<br />\n";
echo "Создан: <strong>".vremja($clan['time'])."</strong><br />\n";
echo "</div>\n";

echo "<div class=\"str\">\n";
echo "<a href='/klan/news.php'>Новости клана</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_news` WHERE `id_clan` = '$clan[id]'"),0).")<br/>\n";
echo "<a href='/klan/adm.php'>Администрация клана</a><br/>\n";
echo "<a href='/klan/pravila.php'>Правила клана</a><br/>\n";
echo "<a href='/klan/chat.php'>Чат клана</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_chat` WHERE `id_clan` = '$clan[id]'"),0)."/+".mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_chat` WHERE `id_clan` = '$clan[id]' AND `time` > '".(time()-86400)."'"),0).")<br/>\n";
echo "<a href='/klan/jurnal.php'>События клана</a> (+".mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_jurnal` WHERE `id_clan` = '$clan[id]' AND `time` > '".(time()-86400)."'"),0).")<br/>\n";
echo "<a href='/klan/user.php'>Обитатели клана</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_clan` = '$clan[id]' AND `activaty` = '0'"),0).")<br/>\n";
if(mysql_result(mysql_query("SELECT COUNT(id) FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"),0)!=0)echo "<a href='/klan/enter.php'>Выйти из клана</a><br/>\n";
echo "</div>\n";
}
echo "<div class='str'>";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>