<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/adm_check.php';
include_once '../../sys/inc/user.php';

$set['title']='Оценка 5+';
include_once '../../sys/inc/thead.php';
title();
if (!isset($user))
header("location: /index.php?");

err();
aut();

if (isset($user))
{
if (isset($_POST['stav']))
{
if ($_POST['stav']==1)
{
$st=10;
$tm=$time+86400;
}
else if ($_POST['stav']==2)
{
$st=20;
$tm=$time+172800;
}
else if ($_POST['stav']==3)
{
$st=30;
$tm=$time+259200;
}
else if ($_POST['stav']==4)
{
$st=40;
$tm=$time+345600;
}
else if ($_POST['stav']==5)
{
$st=50;
$tm=$time+432000;
}
else if ($_POST['stav']==6)
{
$st=60;
$tm=$time+518400;
}
else if ($_POST['stav']==7)
{
$st=70;
$tm=$time+604800;
}
if ($user['balls']>=$st)
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `ocenky` WHERE `id_user` = '$user[id]'"), 0)==0)
{
mysql_query("INSERT INTO `ocenky` (`id_user`, `stav`, `time`) values('$user[id]', '$st', '$tm')");
}
else
{
mysql_query("UPDATE `ocenky` SET `time` = '$tm', `stav` = '$st' WHERE `id_user` = '$user[id]'");
}
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$st)."' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['message'] = 'Услуга успешно подключена';
header("Location: $_SESSION[url]");
}else{
$err='У вас не достаточно средств';
}
}
err();

echo"<div class='main_menu'>\n";
echo "Услуга <img src='/style/icons/6.png' alt='*'><br /> 10 баллов = 1 день пользования превилегией.";
echo"</div>\n";

echo "<form method=\"post\" action=\"?\">\n";
	echo 'Ставка: <select name="stav">
	<option value="1">10</option>
	<option value="2">20</option>
	<option value="3">30</option>
	<option value="4">40</option>
	<option value="5">50</option>
	<option value="6">60</option>
	<option value="7">70</option>
	</select> баллов<br />';

echo "<input value=\"Купить услугу\" type=\"submit\" />\n";
echo "</form>\n";
}



echo "<div class='foot'>\n";
echo "&laquo;<a href='/info.php'>Моя страница</a><br />\n";
echo "</div>\n";

include_once '../../sys/inc/tfoot.php';
?>