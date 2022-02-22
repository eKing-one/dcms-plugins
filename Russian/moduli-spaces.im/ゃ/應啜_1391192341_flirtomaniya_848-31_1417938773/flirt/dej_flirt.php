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
$set['title']='Админка - Дейстия для флиртa';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if ($user['level']<=3)
{
header("Location: /flirt/index.php");
exit;
}
include_once 'inc/dej.php';
echo "<a href='add_dej.php'><div class='p_t'>";
echo "<img src='img/add.png' alt='Simptom'> Добавить действие";
echo "</div></a>";
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
echo "<div class='p_m'>";
echo "<span style='float : right;'>";
echo "<a href='?id=".$post['id']."&amp;edit'><img src='img/edit.png' alt='Simptom'></a> | ";
echo "<a href='?id=".$post['id']."&amp;del'><img src='img/del.png' alt='Simptom'></a>";
echo "</span>";
echo "<img src='img/flirt.png' alt='Simptom'> ".output_text($post['name'])." (".$post['cena']." флиртонов)";
echo "</div>";
}
if ($k_page>1)
{
str('?',$k_page,$page);
}
echo "<a href='admin.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>