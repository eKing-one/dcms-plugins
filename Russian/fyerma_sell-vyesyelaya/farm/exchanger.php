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
$set['title']='欢乐农场 :: 交换所';
include_once '../sys/inc/thead.php';
title();
aut();

if (isset($_GET['gems_exchange']) && isset($_POST['gems']) && is_numeric($_POST['gems']) && $_POST['gems']>0)
{
$gemsp=intval($_POST['gems']);
$needb=$gemsp*1000;
if ($user['balls']<$needb)
{
add_farm_event('У Вас не хватает баллов для совершения данной операции');
}
else
{
dbquery("UPDATE `user` SET `balls` = `balls`-'$needb' WHERE `id` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'$gemsp' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы успешно приобрели '.$gemsp.' алмазов, потратив при этом '.$needb.' баллов сайта');
}
}

if (isset($_GET['gold_exchange']) && isset($_POST['balls']) && is_numeric($_POST['balls']) && $_POST['balls']>0)
{
$balls=intval($_POST['balls']);
$gemsp=$balls*10;
if ($user['balls']<$balls)
{
add_farm_event('У Вас не хватает баллов для совершения данной операции');
}
else
{
dbquery("UPDATE `user` SET `balls` = `balls`-'$balls' WHERE `id` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `gold` = `gold`+'$gemsp' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы успешно приобрели '.$gemsp.' монет, потратив при этом '.$balls.' баллов сайта');
}
}

include_once 'inc/str.php';
farm_event();

if (isset($_GET['gems']))
{
echo "<div class='rowup'>";
echo "&raquo; На Вашем счету: <img src='/farm/img/gems.png' alt='' class='rpg' />".sklon_after_number("$fuser[gems]","алмаз","алмаза","алмазов",1)."<br />";
echo "&raquo; На Вашем счету: <img src='/farm/img/money.png' alt='' class='rpg' />".sklon_after_number("$user[balls]","балл","балла","баллов",1)." сайта<br />";
echo "&raquo; Один алмаз стоит 1000 баллов сайта<br />";
echo "<form action='?gems_exchange' method='post'>";
echo "&raquo; Введите количество алмазов:<br />";
echo "<input type='text' name='gems' value='10' /><br /><input type='submit' value='Обменять' /></form>";
echo "</div>";
}
if (isset($_GET['gold']))
{
echo "<div class='rowup'>";
echo "&raquo; На Вашем счету: <img src='/farm/img/money.png' alt='' class='rpg' />".sklon_after_number("$fuser[gold]","монета","монеты","монет",1)."<br />";
echo "&raquo; На Вашем счету: <img src='/farm/img/money.png' alt='' class='rpg' />".sklon_after_number("$user[balls]","балл","балла","баллов",1)." сайта<br />";
echo "&raquo; Одна золотая монета будет стоить 0.1 балла сайта<br />";
echo "<form action='?gold_exchange' method='post'>";
echo "&raquo; Введите количество баллов сайта для обмена:<br />";
echo "<input type='text' name='balls' value='10' /><br /><input type='submit' value='Обменять' /></form>";
echo "</div>";
}

echo "<div class='rowdown'>";
echo "<img src='/farm/img/gems.png' alt='' class='rpg' /> <a href='?gems'>Обменять баллы сайта на алмазы</a> (1000:1)";
echo "</div>";
echo "<div class='rowup'>";
echo "<img src='/farm/img/money.png' alt='' class='rpg' /> <a href='?gold'>Обменять баллы сайта на монеты</a> (1:10)";
echo "</div>";
include_once '../sys/inc/tfoot.php';
?>