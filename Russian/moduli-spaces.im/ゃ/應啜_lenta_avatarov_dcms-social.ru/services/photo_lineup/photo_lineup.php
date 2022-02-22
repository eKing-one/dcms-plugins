<?php
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';

include_once '../../sys/inc/thead.php';
include_once '../../services/photo_lineup/photo_lineup.inc.php';
$set['title']='Фото линейка';
title();
err();

Error_Reporting(E_ALL & ~E_NOTICE);

if(isset($_GET['ok'])){echo "<div class='msg'>Вы успешно попали в фото-линейку</div>\n";}

$id=intval($_GET['id']);
$arr=mysql_fetch_array(mysql_query("SELECT * FROM `photo_lineup` WHERE `id`='$id'"));
$action=htmlspecialchars(trim($_GET['action']));
switch ($action)	{
default:
echo "<div class='$dizz'>
Ваша фотография будет размещена в фото-линейки на главной странице сайта.<br >
Услуга стоит: <b>".$cena1."</b> монет<br>
У вас: <b>".$user['money']."</b> монет.<br>
Ваша фотография будет на главной странице показываться до тех пор пока другие пользователи линейку не сдвинут!
</div>\n";

echo "<div class='$diz'><a href='?action=1'>Активировать</a></div>";
break;
case '1':
if ($user['money']>=$cena1)		{
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$cena1)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("DELETE FROM `photo_lineup` WHERE `id_user` = '$user[id]'");
mysql_query("INSERT INTO `photo_lineup` (`id_user`, `time`) values('$user[id]', '".time()."')");

header('Location: ?ok');}	else	{
echo "<div class='err'>У вас нету ".$cena1." монет!</div>\n";}	
break;

	}
include_once '../../sys/inc/tfoot.php';
?>