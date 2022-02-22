<?php



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







$set['title']='VIP Премиум';



include_once '../../sys/inc/thead.php';











 if ($user['id']<1) header('Location: /index.php');
$cena1 = '100';#цена статуса 
$cena2 = '100';#цена статуса 
$cena3 = '100';#цена статуса 
$cena4 = '100';#цена статуса 
$cena5 = '100';#цена статуса 
$cena6 = '100';#цена статуса 

$vremya1 = '604801';#Время до отключения
$vremya2 = '604801';#Время до отключения
$vremya3 = '604801';#Время до отключения
$vremya4 = '604801';#Время до отключения
$vremya5 = '604801';#Время до отключения
$vremya6 = '604801';#Время до отключения

$msgs = '<div class="err">У вас уже есть VIP Премиум!</div>';
function otime($timediff)	{
$oneMinute=60;
$oneHour=60*60;
$oneDay=60*60*24;
$dayfield=floor($timediff/$oneDay);
$hourfield=floor(($timediff-$dayfield*$oneDay)/$oneHour);
$minutefield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute);
$secondfield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute));
$time_1="$dayfield дней. $hourfield ч. $minutefield м. $secondfield сек.";
return $time_1;
}
  function text_out($text) { $text = stripslashes(htmlspecialchars(trim($text))); return $text; } # Обработка текста

$arrs=mysql_fetch_array(mysql_query("SELECT * FROM`vip_premimum` WHERE `id_user`='".$user['id']."'"));










title();
err();
aut();

/////43200


#---#
Error_Reporting(E_ALL & ~E_NOTICE);
#---#
if(isset($_GET['cl'])){	msg('VIP Премиум  удален');}
if(isset($_GET['clean'])){
if(isset($_GET['ok'])){
mysql_query("DELETE FROM `vip_premimum` WHERE `id_user` = '$user[id]'");
header('Location: ?cl');}	else	{
echo "<div class='err'>Вы уверены что хотите удалить VIP Премиум ? <a href='?clean&amp;ok'>Да</a> : <a href='?'>Нет</a></div>\n";}}
#---#
if(isset($_GET['ok'])){echo "<div class='msg'>Вы успешно приобрели VIP Премиум </div>\n";}
#---#

#---#
$id=intval($_GET['id']);
$arr=mysql_fetch_array(mysql_query("SELECT * FROM `vip_premimum` WHERE `id`='$id'"));

#---#
$action=htmlspecialchars(trim($_GET['action']));
switch ($action)	{
default:
echo "<div class='foot'>
 <img src='/style/vip/mon.png'><b>У вас: $user[money]</b> монет.<br /><img src='/style/vip/mon.png'><font color='red'>Стоимость VIP  Премиум составляет 100 монет.</font></div><div class='foot'>
<b>Преимущества VIP Премиум !
</b>
<br />

<img src='/style/vip/1.png'> иконка возле ника на выбор из 6 штук
<br />





 
<img src='/style/vip/lid.png'>услуга лидер сайта
<br />

<img src='/style/vip/o.png'>услуга оценка +5
<br />
<img src='/style/vip/+10.png'>услуга поднятия рейтинка за страничку +10
<br />

Ваш  мини аватар на главной по очереди меняется с остальными  VIP Премиум<br />
Возможность удалить VIP Премиум абсолютно бесплатно
<br />
VIP Премиум предоставляется на одну неделю.<br />
								<br />

<font color='red'>Все это действует пока пользователь VIP Премиум</font>
<br />



</div>\n";

#---#

if($arrs['time']>=$time)	{
echo '<div class="msg">Вашему VIP Премиум осталось жить ещё '.otime($arrs["time"]-time()).'</div>';

echo '<div class="nav2"><img src="/style/vip/1.png"><font color="red"><b>Вы</font><font color="GREEN"> VIP Премиум</font> </a></b> </div>';
}	else	{
echo "<div class='nav1'><img src='/style/vip/1.png'><br /><a href='?action=1'><img src='/style/vip/mon.png'>Купить</a></div>
<div class='nav2'><img src='/style/vip/2.png'><br /><a href='?action=2'><img src='/style/vip/mon.png'>Купить </a></div>
<div class='nav1'><img src='/style/vip/3.png'><br /><a href='?action=3'><img src='/style/vip/mon.png'>Купить </a></div>
<div class='nav2'><img src='/style/vip/4.png'><br /><a href='?action=4'><img src='/style/vip/mon.png'>Купить </a></div>
<div class='nav1'><img src='/style/vip/5.png'><br /><a href='?action=5'><img src='/style/vip/mon.png'>Купить</a></div>
<div class='nav2'><img src='/style/vip/6.png'><br /><a href='?action=6'><img src='/style/vip/mon.png'>Купить</a></div>";
}
break;
#---#
case '1':
if($arrs['time']>=$time)	{
echo $msgs.'';}	else	{
if ($user['money']>$cena1)		{
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$cena1)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("DELETE FROM `vip_premimum` WHERE `id_user` = '$user[id]'");
mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`) values('$user[id]', '7', 'VIP Премиум!', '".($time+604800)."')");

$tm=$time+604800;

mysql_query("UPDATE `user_set` SET `ocenka` = '".($time+604800)."' WHERE `id_user` = '$user[id]'");










mysql_query("INSERT INTO `vip_premimum` (`id_user`,  `nomer`, `time`) values('$user[id]', '1', '".($time+$vremya1)."')");
header('Location: ?ok');

mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$user[id]', 'Вы активировали услугу VIP Премиум . С вашего счета было снято ".$cena1." монет', '$time')");
	header("Location: /info.php?id=$user[id]");

}	else	{
echo "<div class='err'>У вас нету ".$cena1." монет!</div>\n";}	}break;
#---#
case '2':
if($arrs['time']>=$time)	{
echo $msgs.'';}	else	{
if ($user['money']>$cena2)		{
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$cena2)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("DELETE FROM `vip_premimum` WHERE `id_user` = '$user[id]'");


mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`) values('$user[id]', '7', 'VIP Премиум!', '".($time+604800)."')");

$tm=$time+604800;

mysql_query("UPDATE `user_set` SET `ocenka` = '".($time+604800)."' WHERE `id_user` = '$user[id]'");






mysql_query("INSERT INTO `vip_premimum` (`id_user`, `nomer`, `time`) values('$user[id]', '2', '".($time+$vremya2)."')");
header('Location: ?ok');


mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$user[id]', 'Вы активировали услугу VIP Премиум . С вашего счета было снято ".$cena1." монет', '$time')");
	header("Location: /info.php?id=$user[id]");






}	else	{
echo "<div class='err'>У вас нету ".$cena2." монет!</div>\n";}	}break;
case '3':
if($arrs['time']>=$time)	{
echo $msgs.'';}	else	{
if ($user['money']>$cena3)	{
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$cena3)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("DELETE FROM `vip_premimum` WHERE `id_user` = '$user[id]'");

mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`) values('$user[id]', '7', 'VIP Премиум!', '".($time+604800)."')");

$tm=$time+604800;

mysql_query("UPDATE `user_set` SET `ocenka` = '".($time+604800)."' WHERE `id_user` = '$user[id]'");








mysql_query("INSERT INTO `vip_premimum` (`id_user`, `nomer`, `time`) values('$user[id]', '3', '".($time+$vremya3)."')");
header('Location: ?ok');




mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$user[id]', 'Вы активировали услугу VIP Премиум . С вашего счета было снято ".$cena1." монет', '$time')");
	header("Location: /info.php?id=$user[id]");







}	else	{
echo "<div class='err'>У вас нету ".$cena3." монет!</div>\n";}	}break;
#---#
case '4':
if($arrs['time']>=$time)	{
echo $msgs.'';}	else	{
if ($user['money']>$cena4)		{
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$cena4)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("DELETE FROM `vip_premimum` WHERE `id_user` = '$user[id]'");

mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`) values('$user[id]', '7', 'VIP Премиум!', '".($time+604800)."')");

$tm=$time+604800;

mysql_query("UPDATE `user_set` SET `ocenka` = '".($time+604800)."' WHERE `id_user` = '$user[id]'");






mysql_query("INSERT INTO `vip_premimum` (`id_user`, `nomer`, `time`) values('$user[id]', '4', '".($time+$vremya4)."')");
header('Location: ?ok');

mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$user[id]', 'Вы активировали услугу VIP Премиум . С вашего счета было снято ".$cena1." монет', '$time')");
	header("Location: /info.php?id=$user[id]");





}else{
echo "<div class='err'>У вас нету ".$cena4." монет!</div>\n";}	}break;
#---#
case '5':
if($arrs['time']>=$time)	{
echo $msgs.'';}	else	{
if ($user['money']>$cena5)		{
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$cena5)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("DELETE FROM `vip_premimum` WHERE `id_user` = '$user[id]'");

mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`) values('$user[id]', '7', 'VIP Премиум!', '".($time+604800)."')");

$tm=$time+604800;

mysql_query("UPDATE `user_set` SET `ocenka` = '".($time+604800)."' WHERE `id_user` = '$user[id]'");




mysql_query("INSERT INTO `vip_premimum` (`id_user`, `nomer`, `time`) values('$user[id]', '5', '".($time+$vremya5)."')");
header('Location: ?ok');}	else	{
echo "<div class='err'>У вас нету ".$cena5." монет!</div>\n";}	}break;
#---#
case '6':
if($arrs['time']>=$time)	{
echo $msgs.'';}	else	{
if ($user['money']>$cena6)		{
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$cena6)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("DELETE FROM `vip_premimum` WHERE `id_user` = '$user[id]'");

mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`) values('$user[id]', '7', 'VIP Премиум!', '".($time+604800)."')");

$tm=$time+604800;

mysql_query("UPDATE `user_set` SET `ocenka` = '".($time+604800)."' WHERE `id_user` = '$user[id]'");






mysql_query("INSERT INTO `vip_premimum` (`id_user`, `nomer`, `time`) values('$user[id]', '6', '".($time+$vremya6)."')");
header('Location: ?ok');

mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$user[id]', 'Вы активировали услугу VIP Премиум . С вашего счета было снято ".$cena1." монет', '$time')");
	header("Location: /info.php?id=$user[id]");








}	else	{	
echo "<div class='err'>У вас нету ".$cena6." монет!</div>\n";}	}break;
	
	}
#---#
if($arrs['time']>=$time)	{
echo "<div class='nav1'>\n";
echo "<a href='?clean'><img src='/style/vip/11.png'>Удалить VIP Премиум</a><br />\n";
echo "</div>\n";}	else	{}
#---#
include_once '../../sys/inc/tfoot.php';
?>