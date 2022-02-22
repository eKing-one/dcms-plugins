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
$set['title']='Финансы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();

echo "<div class='rowup'>\n";
echo "<span class=\"status\">На счету $user[balls] Баллов</span><br/>";
echo "</div>\n";


echo "<img src='/finance/icons/bank.png'><a href='/finance/uslugi.php'><b> Услуги за Баллы</b></a><br/>";

echo "<img src='/finance/icons/balance.png'><a href='/finance/perevod.php'><b> Перевод Баллов</b></a><br/>";


include_once '../sys/inc/tfoot.php';
?>