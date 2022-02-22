<?
/*
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
$set['title']='Заявки';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
if (!$b)
{
echo "<div class='err'>";
echo "У вас нет ребёнка!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['id']) && (isset($_GET['yes']) || isset($_GET['no'])))
{
$it=intval($_GET['id']);
$pb=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby_sp` WHERE `id` = '".$it."' LIMIT 1"));
$ankw=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$pb['id_user']."' LIMIT 1"));
if ($pb['id']==0 || !$pb)
{
echo "<div class='err'>";
echo "Заявка не найдена!";
echo "</div>";
echo "<a href='zayavki.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if ($ankw['id']==0 || !$ankw)
{
mysql_query("DELETE FROM `baby_sp` WHERE `id` = '".$pb['id']."' LIMIT 1");
echo "<div class='err'>";
echo "Обитатель не найден!";
echo "</div>";
echo "<a href='zayavki.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if ($pb['id_baby']!=$b['id'])
{
echo "<div class='err'>";
echo "Это не ваш ребёнок!";
echo "</div>";
echo "<a href='zayavki.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['yes']))
{
if ($ankw['pol']==0 && $b['mama']==0)
{
mysql_query("UPDATE `baby` SET `mama` = '".$ankw['id']."' WHERE `id` = '".$b['id']."' LIMIT 1");
}
else if ($ankw['pol']==1 && $b['papa']==0)
{
mysql_query("UPDATE `baby` SET `papa` = '".$ankw['id']."' WHERE `id` = '".$b['id']."' LIMIT 1");
}
$mgs="Обитатель ".$user['nick']." принял вашу заявку в родители для своего ребёнка. [url=/baby/my_baby.php]Подробнее...[/url]";
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `time`, `msg`, `read`) VALUES ('0', '".$ankw['id']."', '".$time."', '".$mgs."', '0')");
mysql_query("DELETE FROM `baby_sp` WHERE `id` = '".$pb['id']."'");
echo "<div class='msg'>";
echo "Заявка успешно принята!";
echo "</div>";
echo "<a href='zayavki.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['no']))
{
mysql_query("DELETE FROM `baby_sp` WHERE `id` = '".$pb['id']."' LIMIT 1");
$mgs="Обитатель ".$user['nick']." отклонил вашу заявку в родители для своего ребёнка. [url=/baby/my_baby.php]Подробнее...[/url]";
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `time`, `msg`, `read`) VALUES ('0', '".$ankw['id']."', '".$time."', '".$mgs."', '0')");
echo "<div class='msg'>";
echo "Заявка успешно отклонена!";
echo "</div>";
echo "<a href='zayavki.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_sp` WHERE `id_baby` = '".$b['id']."'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Пусто";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `baby_sp` WHERE `id_baby` = '".$b['id']."' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$post['id_user']."' LIMIT 1"));
$bb2=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$ank['id']."' OR `papa` = '".$ank['id']."' LIMIT 1"));
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14' style='width: 20%'><center>";
echo "<img src='img/bab.png' width='70' alt='Simptom'>";
echo "</center></td>";
echo "<td class='main'>";
echo "".online($ank['id'])." <a href='/info.php?id=".$ank['id']."'>".$ank['nick']."</a> ";
if ($ank['pol']==$user['pol'] || $bb2)
{
mysql_query("DELETE FROM `baby_sp` WHERE `id` = '".$post['id']."' LIMIT 1");
echo "(Ошибочная заявка)";
}
else if ($ank['pol']==0)
{
echo "(Хочет стать мамой)";
}
else if ($ank['pol']==0)
{
echo "(Хочет стать папой)";
}
echo "<br />";
echo "<img src='img/time.png' alt='Simptom'> ".vremja($post['time'])."<br />";
echo "</td>";
echo "</tr></table>";
if ($ank['pol']!=$user['pol'] && !$bb2)
{
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='main2'><center>";
echo "<a href='?id=".$post['id']."&amp;yes'><img src='img/yes.png' alt='Simptom'> Принять</a>";
echo "</center></td>";
echo "<td class='main2'><center>";
echo "<a href='?id=".$post['id']."&amp;no'><img src='img/no.png' alt='Simptom'> Отклонить</a>";
echo "</center></td>";
echo "</tr></table>";
}
}
if ($k_page>1)
{
str('?',$k_page,$page);
}
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>