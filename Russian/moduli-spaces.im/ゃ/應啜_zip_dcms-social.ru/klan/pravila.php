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

if (isset($_GET['id']))$us['id_clan'] = intval($_GET['id']);
if (!isset($_GET['id']))$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"));

$set['title'] = 'Правила нашего клана';
include_once '../sys/inc/thead.php';
title();
aut();
/*
if (mysql_result(mysql_query("SELECT COUNT(id) FROM `clan_user` where `id_user` = '$user[id]' LIMIT 1"),0)==0){
echo "<div class=\"str\">\n";
echo "Вы не состоите в клане\n";
echo "</div>\n";
}
else
{
*/
$q=mysql_query("SELECT * FROM `clan` WHERE `id` = '$us[id_clan]'");
while ($post = mysql_fetch_array($q))
{
echo "<div class='rowdown'>";

if($post['rules']!=NULL) echo trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($post['rules'])))))))."\n";
else echo "Правила не заполнены, но это не даёт Вам право нарушать основные правила сайта <b>$_SERVER[HTTP_HOST]</b>\n";

echo'</div>';
}

echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>