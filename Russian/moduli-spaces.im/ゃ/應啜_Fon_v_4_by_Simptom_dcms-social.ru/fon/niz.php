<?
//info.php заменяем на свой адрес личной странички юзера
if ($_SERVER['PHP_SELF']=='/info.php' && (is_file(H."fon/img_info/$ank[id].gif") || is_file(H."fon/img_info/$ank[id].jpg") || is_file(H."fon/img_info/$ank[id].png")))
{
echo '</div>';
}
//anketa.php заменяем на свой адрес анкеты юзера
if ($_SERVER['PHP_SELF']=='/anketa.php' && (is_file(H."fon/img_anketa/$ank[id].gif") || is_file(H."fon/img_anketa/$ank[id].jpg") || is_file(H."fon/img_anketa/$ank[id].png")))
{
echo '</div>';
}
include_once 'fon/fon_info/niz.php';
include_once 'fon/fon_anketa/niz.php';
?>