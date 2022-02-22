<?
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

$set['title']='События клана';
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
$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"));

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_jurnal` WHERE `id_clan` = '$us[id_clan]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "Нет новостей\n";
}

$q=mysql_query("SELECT * FROM `clan_jurnal` WHERE `id_clan` = '$us[id_clan]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
if($num==1){ echo "<div class='rowup'>";
$num=0;}
else
{echo "<div class='rowdown'>";
$num=1;}

echo "[".vremja($post['time'])."]\n";
echo trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($post['msg'])))))))."<br />\n";

echo'</div>';
}

if ($k_page>1)str('?',$k_page,$page); // Вывод страниц
}

echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>