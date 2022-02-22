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
$set['title'] = 'Выполнение заданий';
include_once '../sys/inc/thead.php';

title();
aut();
err();
$act = isset ($_GET['act']) ? $_GET['act'] : '';
switch ($act) {

/* ======= zd1 ======= */

case 'zd1' :
mysql_query("UPDATE `user` SET `z1_p` = '".($user['z1_p']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
echo '<div class="nav1">В поле ниже скопируйте ссылку на авто вход. ВНИМАНИЕ! В ссылке, вместо слов "СЮДА_ВАШ_ПАРОЛЬ_ПИШИТЕ", напишите свой пароль.</div>';
echo "<input type='text' value='http://$_SERVER[SERVER_NAME]/?id=$user[id]&amp;pass=СЮДА_СВОЙ_ПАРОЛЬ_ПИШИТЕ' />\n";


break;

/* ======= end ======= */



}

echo '<div class="foot"><font color="red">&bull;</font> <a href="/reit/zadanie.php">В задания</a></div>';
include_once '../sys/inc/tfoot.php';

?>
