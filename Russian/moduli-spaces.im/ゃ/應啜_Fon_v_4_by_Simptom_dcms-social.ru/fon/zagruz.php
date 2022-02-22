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
Error_Reporting(0);
include_once '../sys/inc/thead.php';
$set['title']='Управление фонами';

title();
err();
aut();

echo 'Выберите какой фон вы хотите изменить.<br/>';
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo '<a href="/fon/fon_info.php">Фон странички</a><br>';
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo '<a href="/fon/fon_anketa.php">Фон анкеты</a>';

include_once '../sys/inc/tfoot.php';
?>