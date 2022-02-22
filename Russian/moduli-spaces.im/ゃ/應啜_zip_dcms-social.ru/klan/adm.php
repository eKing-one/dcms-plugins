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

if (isset($_GET['id']))$us['id_clan'] = intval($_GET['id']);
if (!isset($_GET['id']))$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"));
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$us[id_clan]' LIMIT 1"));

$set['title'] = 'Администрация '.$clan['name'];
include_once '../sys/inc/thead.php';
title();
aut();

$adm = mysql_query("SELECT * FROM `clan_user` WHERE  `level` > '0' AND `id_clan` = $clan[id]");
while ($post = mysql_fetch_array($adm))
{
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));

if($num==1){ echo "<div class='rowup'>";
$num=0;}
else
{echo "<div class='rowdown'>";
$num=1;}


/*-----------зебра-----------*/
if ($num==0){
	echo "  <div class='nav1'>\n";
	$num=1;
}elseif ($num==1){
	echo "  <div class='nav2'>\n";
	$num=0;
}
/*---------------------------*/

if ($set['set_show_icon']==2){
	avatar($ank['id']);
}
elseif ($set['set_show_icon']==1)
{
	echo "".status($ank['id'])."";
}


echo group($ank['id'])." <a href='/info.php?id=$ank[id]'>$ank[nick]</a>\n";
echo "".medal($ank['id'])." ".online($ank['id'])."";
echo "<br/>";

if ($ank['pol']==1)
echo "(Муж)";
else
echo "(Жен)";
if ($ank['ank_d_r']!=NULL && $ank['ank_m_r']!=NULL && $ank['ank_g_r']!=NULL){
$ank['ank_age']=date("Y")-$ank['ank_g_r'];
if (date("n")<$ank['ank_m_r'])$ank['ank_age']=$ank['ank_age']-1;
elseif (date("n")==$ank['ank_m_r']&& date("j")<$ank['ank_d_r'])$ank['ank_age']=$ank['ank_age']-1;
echo ",$ank[ank_age] лет";
}
if ($ank['ank_city']!=0)echo ",".oncity($ank['id'])."";
echo "<br/>";
echo "Рейтинг: <b>$ank[rating]</b><br />\n";
echo "Должность:\n";
if ($post['level']==2) echo "<span style='color:red;'>Администратор</span>\n";
if ($post['level']==1) echo "<span style='color:red;'>Модератор</span>\n";
echo'</div>'; 
}

echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>