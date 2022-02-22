<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/shif.php';
include_once '../sys/inc/adm_check.php';
include_once '../sys/inc/user.php';
user_access('user_prof_edit',null,'index.php?'.SID);
adm_check();
if (isset($_GET['id']))$ank['id']=intval($_GET['id']);
else {header("Location: /index.php?".SID);exit;}
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]' LIMIT 1"),0)==0)
{
header("Location: /index.php?".SID);
exit;
}
$ank=get_user($ank['id']);
if ($user['level']<=$ank['level'])
{
header("Location: /index.php?".SID);
exit;
}
$set['title']='Профиль пользователя '.$ank['nick'];
include_once '../sys/inc/thead.php';
title();
if (isset($_POST['save']))
{
if (isset($_POST['fon_info_zapret']) && ($_POST['fon_info_zapret']==0 || $_POST['fon_info_zapret']==1))
{
$ank['fon_info_zapret']=$_POST['fon_info_zapret'];
mysql_query("UPDATE `user` SET `fon_info_zapret` = '$ank[fon_info_zapret]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка!';
if (isset($_POST['fon_anketa_zapret']) && ($_POST['fon_anketa_zapret']==0 || $_POST['fon_anketa_zapret']==1))
{
$ank['fon_anketa_zapret']=$_POST['fon_anketa_zapret'];
mysql_query("UPDATE `user` SET `fon_anketa_zapret` = '$ank[fon_anketa_zapret]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка!';
if (isset($_POST['fon_info_vibor']) && ($_POST['fon_info_vibor']==0 || $_POST['fon_info_vibor']==1))
{
$ank['fon_info_vibor']=$_POST['fon_info_vibor'];
mysql_query("UPDATE `user` SET `fon_info_vibor` = '$ank[fon_info_vibor]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка!';
if (isset($_POST['fon_anketa_vibor']) && ($_POST['fon_anketa_vibor']==0 || $_POST['fon_anketa_vibor']==1))
{
$ank['fon_anketa_vibor']=$_POST['fon_anketa_vibor'];
mysql_query("UPDATE `user` SET `fon_anketa_vibor` = '$ank[fon_anketa_vibor]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка!';
if (isset($_POST['fon_vugr_zapret']) && ($_POST['fon_vugr_zapret']==0 || $_POST['fon_vugr_zapret']==1))
{
$ank['fon_vugr_zapret']=$_POST['fon_vugr_zapret'];
mysql_query("UPDATE `user` SET `fon_vugr_zapret` = '$ank[fon_vugr_zapret]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка!';
if (isset($_POST['fon_vugr_zapret2']) && ($_POST['fon_vugr_zapret2']==0 || $_POST['fon_vugr_zapret2']==1))
{
$ank['fon_vugr_zapret2']=$_POST['fon_vugr_zapret2'];
mysql_query("UPDATE `user` SET `fon_vugr_zapret2` = '$ank[fon_vugr_zapret2]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка!';
admin_log('Пользователи','Профиль',"Редактирование профиля пользователя '$ank[nick]' (id#$ank[id])");
if (!isset($err))msg('Изменения успешно приняты');
}
err();
aut();
echo "<form method='post' action='fon_user.php?id=$ank[id]'>\n";
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo "Покупка фона на личную страничку:<br />\n<select name=\"fon_info_zapret\">\n";
if ($ank['fon_info_zapret']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Разрешено</option>\n";
if ($ank['fon_info_zapret']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>Запрещено</option>\n";
echo "</select><br />\n";
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo "Покупка фона в анкету:<br />\n<select name=\"fon_anketa_zapret\">\n";
if ($ank['fon_anketa_zapret']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Разрешено</option>\n";
if ($ank['fon_anketa_zapret']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>Запрещено</option>\n";
echo "</select><br />\n";
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo "Продажа фона личной странички:<br />\n<select name=\"fon_info_vibor\">\n";
if ($ank['fon_info_vibor']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Разрешено</option>\n";
if ($ank['fon_info_vibor']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>Запрещено</option>\n";
echo "</select><br />\n";
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo "Продажа фона анкеты:<br />\n<select name=\"fon_anketa_vibor\">\n";
if ($ank['fon_anketa_vibor']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Разрешено</option>\n";
if ($ank['fon_anketa_vibor']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>Запрещено</option>\n";
echo "</select><br />\n";
echo "Выгрузка фона на личную страничку:<br />\n<select name=\"fon_vugr_zapret\">\n";
if ($ank['fon_vugr_zapret']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Разрешено</option>\n";
if ($ank['fon_vugr_zapret']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>Запрещено</option>\n";
echo "</select><br />\n";
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo "Выгрузка фона в анкету:<br />\n<select name=\"fon_vugr_zapret2\">\n";
if ($ank['fon_vugr_zapret2']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Разрешено</option>\n";
if ($ank['fon_vugr_zapret2']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>Запрещено</option>\n";
echo "</select><br />\n";
echo "<input type='submit' name='save' value='Сохранить' />\n";
echo "</form>\n";
echo "<div class='foot'>\n";
echo '<img src="/fon/ico/go.gif" alt=""/> ';
echo "<a href=\"/info.php?id=$ank[id]\">В анкету</a><br />\n";
if (user_access('adm_panel_show'))
{
echo '<img src="/fon/ico/go.gif" alt=""/> ';
echo "&laquo;<a href='/adm_panel/'>В админку</a><br />\n";
}
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>