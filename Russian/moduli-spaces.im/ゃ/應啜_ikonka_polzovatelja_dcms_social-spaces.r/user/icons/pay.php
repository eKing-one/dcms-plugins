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

$set['title']='Иконки';

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

$GetIcon = intval($_GET['id']);

if($GetIcon < 1 or $GetIcon > 125)  header('Location: index.php'.SID);


echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="S"/> <a href="/user/money/">Дополнительные услуги</a> | <b>Покупка иконки</b>';
echo '</div>';

echo '<div class="mess">';
echo '<span class="mess"><b> Магазин </b></span>';
echo '<a href="index.php?get=my_icons"><span class="mess"><b> Мои иконки </b></span></a>';
echo '</div>';


if(isset($_POST['day']) and (isset($_GET['get']))) {

if($_POST['day'] == 'd7') 
{
if($user['money'] >= $d7) 
{
$d7_time = $time+604800;
mysql_query("DELETE FROM `us_icons` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d7)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `us_icons` (`id_user`, `id_icon`, `time`) VALUES ('".$user['id']."', '".$GetIcon."', '".$d7_time."')"); 
header('Location: index.php?get=my_icons'.SID);
$_SESSION['message']='Иконка успешно приобретена!';

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
mysql_query("DELETE FROM `us_icons` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d30)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `us_icons` (`id_user`, `id_icon`, `time`) VALUES ('".$user['id']."', '".$GetIcon."', '".$d30_time."')"); 
header('Location: index.php?get=my_icons'.SID);
$_SESSION['message']='Иконка успешно приобретена!';

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
mysql_query("DELETE FROM `us_icons` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d180)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `us_icons` (`id_user`, `id_icon`, `time`) VALUES ('".$user['id']."', '".$GetIcon."', '".$d180_time."')"); 
header('Location: index.php?get=my_icons'.SID);
$_SESSION['message']='Иконка успешно приобретена!';

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
mysql_query("DELETE FROM `us_icons` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d365)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `us_icons` (`id_user`, `id_icon`, `time`) VALUES ('".$user['id']."', '".$GetIcon."', '".$d365_time."')"); 
header('Location: index.php?get=my_icons'.SID);
$_SESSION['message']='Иконка успешно приобретена!';

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
mysql_query("DELETE FROM `us_icons` WHERE `id_user` = '".$user['id']."'");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$d0)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `us_icons` (`id_user`, `id_icon`, `time`) VALUES ('".$user['id']."', '".$GetIcon."', '".$d0_time."')"); 
header('Location: index.php?get=my_icons'.SID);
$_SESSION['message']='Иконка успешно приобретена!';

}
else
{
echo '<div class="err"> Недостаточно средств!</div>';
}
}

}

echo '<div class="mess">';

echo '<b>Выбрали:</b> <img src="png/'.$GetIcon.'.png">';
echo '<hr>';
echo '<b>Стоимость использования:</b>';

echo '<form action="pay.php?get=ok&id='.$GetIcon.'" method="POST">
<input type="radio" name="day" value="d7" checked="checked"> 7 дней - '.$d7.' монет<Br><hr>
<input type="radio" name="day" value="d30"> 1 месяц - '.$d30.' монет<Br><hr>
<input type="radio" name="day" value="d180"> 6 месяцев - '.$d180.' монет<Br><hr>
<input type="radio" name="day" value="d365"> 1 год - '.$d365.' монет<Br><hr>
<input type="radio" name="day" value="d0"> навсегда - '.$d0.' монет<Br><hr>
<input type="submit" value="Купить">
</form>';

echo '</div>';




echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="S"/> <a href="/user/money/">Дополнительные услуги</a> | <b>Покупка иконки</b>';
echo '</div>';


err();
include_once '../../sys/inc/tfoot.php';
?>