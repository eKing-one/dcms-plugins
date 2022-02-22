<?
/*
Модуль: Мои гости
Версия: 1
Автор: Merin
Аська: 7950048
*/
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
include_once 'sys/inc/my_guest.php';
only_reg();

$set['title']='Услуга "Невидимка"';
include_once 'sys/inc/thead.php';
title();
$newid = mysql_fetch_array(mysql_query("SELECT * FROM `newid` WHERE `uid` = '".$user['id']."'"));
if (isset($_POST['save'])){
if ($newid==0) {
if (!isset($_POST['period']))$err='Не выбрано время действия услуги!';
$period=intval($_POST['period']);
if ($period==604800)$c=400;
if ($period==864000)$c=600;
if ($period==2592000)$c=1000;
if ($user['balls']<$c)$err='Не хватает баллов! '.ball($c-$user['balls']).'';
if (!isset($err)) {
$ball=$user['balls']-$c;
$tim=$time+$period;
mysql_query("INSERT INTO `newid` (`uid`, `time`) values('".$user['id']."', '".$tim."')");
mysql_query("UPDATE `user` SET `balls` = '".$ball."' WHERE `id` = '".$user['id']."'");
msg('Услуга успешно активированна до '.vremja($tim).'');
}
}else {
$err='Ошибка! У вас услуга уже активированна!';
}
}
err();
aut();
$newid = mysql_fetch_array(mysql_query("SELECT * FROM `newid` WHERE `uid` = '".$user['id']."'"));
if ($newid!=0) {
echo "Вы уже активировали услугу <b>\"Невидимка\"</b><br />\n";
echo "Окончания действия услуги ".vremja($newid['time'])."<br />\n";
include_once 'sys/inc/tfoot.php';
exit();
}
echo "<div align='center'>";
echo "Здесь вы можете активировать услугу<br /><b>\"Невидимка\"</b><br />\n";
echo "</div>";
echo "<div class='post'>";
echo "Выберите время на какое активируйте услугу!";
echo "<form method='post' action='?$passgen'>\n";
echo "<select size='1' name='period'>";
echo "<option value='604800'>7 дней (".ball('400').")</option>";
echo "<option value='864000'>10 дней (".ball('600').")</option>";
echo "<option value='2592000'>30 дней (".ball('1000').")</option>";
echo "</select><br />\n";
echo "<input type='submit' name='save' value='Активировать!' />\n";
echo "</form>\n";
echo "</div>";
echo "<div class='foot'>";
echo "Активация услуги \"Невидимка\" дает возможность просматривать анкеты пользователей и оставаться не замеченными!";
echo "</div>";
include_once 'sys/inc/tfoot.php';
?>