и<?php
##############################
#  Модуль покупки рекламы    #
#Автор Sakamsky99 (Sakamsky) #
#Скрипт запрещено продавать, #
#дарить, кидать в пабл!      #
##############################
require_once ('../WebMoney/core.php');
$wmr_koch = $_POST['LMI_PAYEE_PURSE']; // Кошелек
$summa = $_POST['LMI_PAYMENT_AMOUNT']; // Входящая сумма
$wmidpokupatela = $_POST['LMI_PAYER_WM']; // WMID
$tel = $_POST['LMI_WMCHECK_NUMBER']; // Телефон
$email = $_POST['LMI_PAYMER_EMAIL']; // Email
$name = $_POST['name']; // Название
$url = $_POST['url']; // URL
$days= $_POST['days']; // Сутки
$color = $_POST['color']; // Цвет
$bold = $_POST['bold']; // Жирность
$date_last=time()+$days*24*60*60;
if (isset($_POST['LMI_PREREQUEST']))
{
// Проверяем на сумму
if(!isset($name))$err = 'Не введено название сайта';
if(!isset($url))$err = 'Не введен адрес сайта';
if(!isset($days))$err = 'Не введено количество суток';

if (isset($err))
{
echo $_POST['LMI_HASH'];
exit;
}
echo 'YES';
exit;
}
$chkstring =
$wmr_k
.$_POST['LMI_PAYMENT_AMOUNT']
.$_POST['LMI_PAYMENT_NO']
.$_POST['LMI_MODE']
.$_POST['LMI_SYS_INVS_NO']
.$_POST['LMI_SYS_TRANS_NO']
.$_POST['LMI_SYS_TRANS_DATE']
.$key
.$_POST['LMI_PAYER_PURSE']
.$_POST['LMI_PAYER_WM'];
$md5sum = strtoupper(hash("sha256", $chkstring));
if($md5sum != $_POST['LMI_HASH'])
{
die('Ошибка');
exit;
}
$stmt = $dbb->prepare("INSERT INTO `webmoney_rek` (`name`, `url`, `date_last`, `color`, `bold`) VALUES (?, ?, ?, ?, ?)");
$stmt -> execute(array($name, $url, $date_last, $color, $bold));
exit;
?>
