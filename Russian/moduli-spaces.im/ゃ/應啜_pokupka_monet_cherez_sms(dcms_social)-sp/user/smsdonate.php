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

$set['title']='Пользователи'; // заголовок страницы

include_once '../sys/inc/thead.php';

title();

aut();
include_once 'smsbillclass.php';
# Подключаем специальный класс SMSBill_getpassword для соединения с сервером билинга
$smsbill = new SMSBill_getpassword();
# Назначаем переменной $smsbill значение класса
$smsbill->setServiceId(9564);
#измените на свой ID. Доступен в Личном кабинете
$smsbill->useEncoding('UTF-8');
# Не меняйте
$smsbill->useHeader('no');
# Не меняйте
$smsbill->useCSS('no');
$smsbill->useLang('ru');
//echo $smsbill->getForm();

# Сколько монет давать.
$cost=1000;
//Вышепредставленных 5 строчек достаточно для полноценной работы скрипта.
if (isset($_REQUEST['smsbill_password'])) {
if (!$smsbill->checkPassword($_REQUEST['smsbill_password'])) {
//введен не верный пароль
echo 'Введенный пароль не верный вернитесь назад и попробуйте еще раз';
}else{

mysql_query("UPDATE `user` SET `money` = `money` + $cost WHERE  `id_user` = '".$user[id]."' LIMIT 1");
}
}else{
//показать платежную форму т.к. пароль еще не был введен
echo $smsbill->getForm();
}

include_once '../sys/inc/tfoot.php';

?>

