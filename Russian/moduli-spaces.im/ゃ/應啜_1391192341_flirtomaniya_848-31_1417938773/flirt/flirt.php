<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Флиртомания';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$it."' LIMIT 1"));
}
if (!isset($_GET['id']) || !$ank || $ank['id']==0 || $user['id']==$ank['id'])
{
echo "<div class='err'>";
echo "Ошибка!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
include_once 'inc/flirt_act.php';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt_flirt`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Действия отсуствуют!";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `flirt_flirt` ORDER BY `id` ASC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
if ($user['flirt_flirtons']>=$post['cena'])
{
echo "<a href='?id=".$ank['id']."&amp;flirt=".$post['id']."'><div class='foot'>";
echo "<img src='img/flirt.png' alt='Simptom'> ".output_text($post['name'])." ";
echo "(<img src='img/flirtons.png' alt='Simptom'> ".$post['cena']." флиртонов)";
echo "</div></a>";
}else{
echo "<div class='foot'>";
echo "<img src='img/flirt.png' alt='Simptom'> ".output_text($post['name'])." ";
echo "(<img src='img/flirtons.png' alt='Simptom'> <font color='red'>".$post['cena']." флиртонов</font>)";
echo "</div>";
}
}
if ($k_page>1)
{
str("?id=".$ank['id']."&amp;",$k_page,$page);
}
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>