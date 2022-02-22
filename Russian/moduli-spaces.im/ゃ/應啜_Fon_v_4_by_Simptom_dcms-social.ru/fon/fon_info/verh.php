<?
//вместо info.php напишите свою ссылку на личную страничку обитателя.
if ($_SERVER['PHP_SELF']=='/info.php')
{
include_once 'fon/fon_info/fon.php';
if ($ank['fon_info_pokaz'] == '0')
{
include_once 'fon/fon_info/verh2.php';
}
if ($ank['fon_info_pokaz'] == '1' && mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '$ank[id]' && `frend` = '$user[id]'"),0) == '0')
{
include_once 'fon/fon_info/verh2.php';
}
}
?>
