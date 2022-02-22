<?

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"));
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$us[id_clan]' LIMIT 1"));

$set['title'] = 'Обитатели клана '.$clan['name'];
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
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE  `id_clan` = '$clan[id]'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
echo "<div class='rowdown'>"; 
echo "В клане $clan[name] нет участников.\n";
echo "</div>"; 
}

$q = mysql_query("SELECT * FROM `clan_user` WHERE  `id_clan` = '$clan[id]' ORDER BY `time` ASC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
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

if ($us['level']==2 && $post['level']==0) echo "[<a href='adminka.php?act=modyes&amp;id=$post[id_clan]&amp;user=$post[id]'>Дать модера</a>]\n";
elseif ($us['level']==2 && $post['level']==1) echo "[<a href='adminka.php?act=modno&amp;id=$post[id_clan]&amp;user=$post[id]'>Снять модера</a>]\n";
if ($us['level']==2) echo "[<a href='adminka.php?act=balls&amp;id=$post[id_clan]&amp;user=$post[id_user]'>Выдать Баллы</a>]\n";
echo'</div>'; 
}

if ($k_page>1)str("?",$k_page,$page); // Вывод страниц
}
echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";


include_once '../sys/inc/tfoot.php';

?>