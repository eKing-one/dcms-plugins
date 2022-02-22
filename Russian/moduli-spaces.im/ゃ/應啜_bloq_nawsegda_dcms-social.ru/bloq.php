<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/shif.php';
include_once 'sys/inc/adm_check.php';
include_once 'sys/inc/user.php';





if (isset($_GET['id']))$ank['id']=intval($_GET['id']);
else {header("Location: /index.php?".SID);exit;}


if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]' LIMIT 1"),0)==0){header("Location: /index.php?".SID);exit;}


$ank=get_user($ank['id']);
if ($user['level']<=$ank['level']){header("Location: /index.php?".SID);exit;}


$set['title']='Профиль пользователя '.$ank['nick'];
include_once 'sys/inc/thead.php';
title();
aut();

if (isset($_POST['save'])){
if (isset($_POST['bloq']) && ($_POST['bloq']==1 || $_POST['bloq']==0))
{
$ank['bloq']=$_POST['bloq'];
mysql_query("UPDATE `user` SET `bloq` = '$ank[bloq]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка режима Заблокировать';
if (isset($_POST['bloq_p']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['bloq_p']))))<=320)
{
$ank['bloq_p']=esc(stripcslashes(htmlspecialchars($_POST['bloq_p'])));
mysql_query("UPDATE `user` SET `bloq_p` = '$ank[bloq_p]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Причина не может быть длиннее 320 символов';
if (!isset($err))msg('Изменения успешно приняты');

}

err();
aut();

echo "<form method='post' action='?id=$ank[id]'>\n";
echo "Вы уверены что хотите заблокировать $ank[nick] навсегда? :<br />\n<select name='bloq'>\n";
echo "<option value='0'".($ank['bloq']==0?" selected='selected'":null).">Нет</option>\n";
echo "<option value='1'".($ank['bloq']==1?" selected='selected'":null).">да</option>\n";

echo "Причина блокировки:<br />\n<input type='text' name='bloq_p' value='$ank[bloq_p]' maxlength='70' /><br />\n";///////////причина
echo "<input type='submit' name='save' value='Сохранить' />\n";
echo "</form>\n";
include_once 'sys/inc/tfoot.php';
?>