<?
#######################################################
### Мод подтверждения номера для DCMS               ###
### Автор:Merin                                     ###
### ICQ:7950048                                     ###
### Вы не имеете права распространять данный скрипт ###
#######################################################
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
$show_all=true; // показ для всех
include_once 'sys/inc/user.php';
only_reg();

$set['title']='Доступ к сайту ограничен!';
include_once 'sys/inc/thead.php';
title();
####################
### Прверка кода ###
####################
if (isset($_POST['cod'])){$code=stripslashes(htmlspecialchars(esc($_POST['kode'])));if ($user['code']!=$code)$err='Неверный код!';
if (!isset($err)) {mysql_query("UPDATE `user` SET `phone_activ` = '0' WHERE `id` = '".$user['id']."' LIMIT 1");$msg_id="Поздравляем! Вы успешно подтвердили свой номер телефона!";
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '".$user['id']."', '".my_esc($msg_id)."', '".$time."')");
header("Location: /index.php");
exit();
}
}
#######################
###     конец       ###
#######################
##############################
### Прверка и запись номера###
##############################
if (isset($_POST['phon'])){$phone=stripslashes(htmlspecialchars(esc($_POST['phone'])));
if (!is_numeric($phone))$err='Номер должен состоять из цифр!';
if (strlen($phone)<9 || strlen($phone)>10)$err='Неверная длина номера!';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `phone` = '".$phone."' LIMIT 1"),0)!=0)$err='Данный номер уже зарегистрирован в системе!';
if (isset($_SESSION['antiflood']) && $_SESSION['antiflood']>$time-600)$err='Повторный запрос!!';
if (!isset($err)) {
$sms=mysql_fetch_array(mysql_query("SELECT * FROM `sms_set` WHERE `id` = 1"));
if ($sms['login']!=0 || $sms['pwd']!=0 || $sms['pod']!=0) {

$kod=rand(111111,999999);
$mes="Ваш код подтверждения: ".$kod."";
$id_mes=rand(100000,999999);
$message=iconv('utf-8', 'windows-1251', $mes);

$p = @file_get_contents('http://websms.ru/http_in5.asp?http_username='.urlencode(trim($sms['login'])).'&http_password='.urlencode(trim($sms['pwd'])).'&Phone_list='.urlencode(trim($phone)).'&packet_id='.urlencode(trim($id_mes)).'&message='.urlencode(trim($message)).'&fromPhone='.urlencode(trim($sms['pod'])).'');
$_SESSION['antiflood']=$time;
$_SESSION['ok']=1;
mysql_query("UPDATE `user` SET `phone` = '".$phone."', `code` = '".$kod."' WHERE `id` = '".$user['id']."' LIMIT 1");
msg('На ваш номер выслан код!');

} else {
$msg_adm="Не удалось отправить код подтверждения пользователю! Не введены данные.";
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '1', '".my_esc($msg_adm)."', '".$time."')");
$err='Ошибка отправки кода!';
}
}
}
#######################
###     конец       ###
#######################
err(); // вывод ошибок
#######################
###    ввод кода    ###
#######################
if ($user['phone']!=0 && $user['phone_activ']!=0) {
echo "<div class='msg'>\n";
echo "На ваш номер <b>".$user['phone']."</b> выслан код, введите его в поле ниже!<br />\n";
echo "</div>\n";
echo "Введите код:<br />\n";
echo "<form method='post' action='?$passgen'>\n";
echo "<input type='text' name='kode' value='' maxlength='6' /><br />\n";
echo "<input type='submit' name='cod' value='Подтвердить!' /><br />\n";
echo "</form>\n";
include_once 'sys/inc/tfoot.php';
exit();
}
#######################
###     конец       ###
#######################
#######################
###   ввод номера   ###
#######################
if ($user['phone']==0 && $user['phone_activ']!=0) {echo "<div class='msg'>\n";
echo "Для пользование всеми возможностями сайта, вам необходимо ввести номер телефона и подтвердить его!<br />\n";
echo "</div>\n";
if (isset($_SESSION['ok'])) {
echo "Код отравлен! Нажмите на <a href='smska.php'>ссылку</a> чтоб ввести код.<br />";
unset($_SESSION['ok']);
} else {
echo "Введите номер телефона: (9503301122)<br />\n";
echo "<form method='post' action='?$passgen'>\n";
echo "<input type='text' name='phone' value='' maxlength='10' /><br />\n";
echo "<input type='submit' name='phon' value='Выслать' /><br />\n";
echo "</form>\n";
echo "<div class='err'>\n";
echo "Внимани! Номер нужно указывать без 8 и 7.<br />Если вы укажете не верный номер телефона, то папасть на сайт вы не сможете!<br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "На указаный номер телефона придет sms с кодом, который будет необходим для подтверждения!<br />\n";
echo "</div>\n";
}
include_once 'sys/inc/tfoot.php';
exit();
}
#######################
###     конец       ###
#######################
#######################
###если все сделанно###
#######################
echo "У вас номер телефона уже подтвержден!<br />\n";
#######################
###     конец       ###
#######################
include_once 'sys/inc/tfoot.php';
?>