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
only_reg();
$set['title']='Услуги за Баллы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();


if (isset($_GET['p']) && !ereg("\.|\/",$_GET['p']) && is_file("uslugi/$_GET[p]"))
{
include "uslugi/$_GET[p]";
echo "<a href=\"index.php\">Назад</a><br/>\n";
include_once 'sys/inc/tfoot.php';
}
else
{
echo "<div class=\"trow\">\n";
echo "<a href=\"index.php\">Финансы</a> &gt; Услуги<br />\n";
echo "</div>\n";

echo "<img src='/finance/icons/asterisk.png'><a href='/finance/uslugi.php?p=rating'><b> Повысить рейтинг</b></a><br/>";
echo "<img src='/finance/icons/asterisk.png'><a href='/finance/uslugi.php?p=color_post'><b> Цвет сообщений</b></a><br/>";

echo "<img src='/finance/icons/asterisk.png'><a href='/finance/uslugi.php?p=color_nick'><b> Цвет ника</b></a><br/>";
}


include_once '../sys/inc/tfoot.php';
?>