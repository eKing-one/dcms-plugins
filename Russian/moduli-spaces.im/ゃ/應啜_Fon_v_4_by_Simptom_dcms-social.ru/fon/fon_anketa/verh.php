<?
//вместо anketa.php напишите свою ссылку на личную анкету обитателя.
if ($_SERVER['PHP_SELF']=='/anketa.php')
{
include_once 'fon/fon_anketa/fon.php';
if ($ank['fon_anketa_pokaz'] == '0')
{
include_once 'fon/fon_anketa/verh2.php';
}
if ($ank['fon_anketa_pokaz'] == '1' && mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '$ank[id]' && `frend` = '$user[id]'"),0) == '0')
{
include_once 'fon/fon_anketa/verh2.php';
}
}
?>
