<?
if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) die;
define('H', $_SERVER['DOCUMENT_ROOT'].'/');
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/home.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';

if (!isset($_GET['id']))
{
	echo 'Ошибка, такого пользователя не существует!';
	exit;
}

$ank = get_user($_GET['id']);

if (!$ank)
{
	echo 'Ошибка, такого пользователя не существует!';
	exit;
}

$msg = my_esc($_POST['msg']);

if (isset($msg) && strlen2($msg) > 1)
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$ank[id]', '$msg', '$time')");
?>