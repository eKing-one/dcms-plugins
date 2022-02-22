<?
//info.php заменяем на свой адрес личной странички юзера
if ($_SERVER['PHP_SELF']=='/info.php')
{
include_once 'fon/verh_info.php';
}

//anketa.php заменяем на свой адрес анкеты юзера
if ($_SERVER['PHP_SELF']=='/anketa.php')
{
include_once 'fon/verh_anketa.php';
}
include_once 'fon/fon_info/verh.php';
include_once 'fon/fon_anketa/verh.php';
?>