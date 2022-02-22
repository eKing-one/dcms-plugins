<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';

$set['title']='Покупка стикеров';

include_once '../../sys/inc/thead.php';

title();
aut();

only_reg();

////////////////
$d7=          5; // 7 дней
$d30=        12; // 1 месяц
$d180=       60; // 6 месяцев
$d365=       99; // 1 год
$d0=        199; // навсегда
///////////////





echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="S"/> <a href="/user/money/">Дополнительные услуги</a> | <b>Покупка стикеров</b>';
echo '</div>';

$st = mysql_result(mysql_query("SELECT COUNT(*) FROM `stickers` WHERE `id_user` = '$user[id]' AND `time` > '$time'"), 0);

if($st == 0) {
if(isset($_POST['day']) and (isset($_GET['get']))) {

if($_POST['day'] == 'd7') 
{
if($user['money'] >= $d7) 
{
$d7_time = $time+604800;
mysql_query("DELETE FROM `stickers` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d7)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `stickers` (`id_user`, `time`) VALUES ('".$user['id']."', '".$d7_time."')"); 
header('Location: ?'.SID);
$_SESSION['message']='Стикеры успешно приобретены!';

}
else
{
echo '<div class="err"> Недостаточно средств!</div>';
}
}

if($_POST['day'] == 'd30') 
{
if($user['money'] >= $d30) 
{
$d30_time = $time+2592000;
mysql_query("DELETE FROM `stickers` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d30)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `stickers` (`id_user`, `time`) VALUES ('".$user['id']."', '".$d30_time."')"); 
header('Location: ?'.SID);
$_SESSION['message']='Стикеры успешно приобретены!';

}
else
{
echo '<div class="err"> Недостаточно средств!</div>';
}
}

if($_POST['day'] == 'd180') 
{
if($user['money'] >= $d180) 
{
$d180_time = $time+15552000;
mysql_query("DELETE FROM `stickers` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d180)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `stickers` (`id_user`, `time`) VALUES ('".$user['id']."', '".$d180_time."')"); 
header('Location: ?'.SID);
$_SESSION['message']='Стикеры успешно приобретены!';

}
else
{
echo '<div class="err"> Недостаточно средств!</div>';
}
}

if($_POST['day'] == 'd365') 
{
if($user['money'] >= $d365) 
{
$d365_time = $time+31536000;
mysql_query("DELETE FROM `stickers` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d365)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `stickers` (`id_user`, `time`) VALUES ('".$user['id']."', '".$d365_time."')"); 
header('Location: ?'.SID);
$_SESSION['message']='Стикеры успешно приобретены!';

}
else
{
echo '<div class="err"> Недостаточно средств!</div>';
}
}


if($_POST['day'] == 'd0') 
{
if($user['money'] >= $d0) 
{
$d0_time = $time+9999999999;
mysql_query("DELETE FROM `stickers` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d0)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `stickers` (`id_user`, `time`) VALUES ('".$user['id']."', '".$d0_time."')"); 
header('Location: ?'.SID);
$_SESSION['message']='Стикеры успешно приобретены!';

}
else
{
echo '<div class="err"> Недостаточно средств!</div>';
}
}

}

echo '<div class="mess">';


echo '<b>Стоимость использования:</b>';

echo '<form action="pay.php?get=ok" method="POST">
<input type="radio" name="day" value="d7" checked="checked"> 7 дней - '.$d7.' монет<Br><hr>
<input type="radio" name="day" value="d30"> 1 месяц - '.$d30.' монет<Br><hr>
<input type="radio" name="day" value="d180"> 6 месяцев - '.$d180.' монет<Br><hr>
<input type="radio" name="day" value="d365"> 1 год - '.$d365.' монет<Br><hr>
<input type="radio" name="day" value="d0"> навсегда - '.$d0.' монет<Br><hr>
<input type="submit" value="Купить">
</form>';

echo '</div>';

}
else
{
$st_us = mysql_fetch_assoc(mysql_query("select * from `stickers` where `id_user` = '".$user['id']."'  "));
echo '<div class="mess">';
echo 'Услуга: <b>Стикеры</b> <span class="on">[активна]</span>';
echo '<br />До: <i><b>'.vremja($st_us['time']).'</b></i> ';
echo '<br /><a href="?delete"> <small>Деактивация</small></a>';
echo '</div>';

if(isset($_GET['delete']))
{
mysql_query("DELETE FROM `stickers` WHERE `id_user` = '".$user['id']."'");
header('Location: ?'.SID);
$_SESSION['message']='Услуга успешно деактивирована!';

}
}


echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="S"/> <a href="/user/money/">Дополнительные услуги</a> | <b>Покупка иконки</b>';
echo '</div>';


err();
include_once '../../sys/inc/tfoot.php';
?>