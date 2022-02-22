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
include_once 'sys/inc/user.php';

only_reg();
$set['title']='Моя анкета';
include_once 'sys/inc/thead.php';
title();


if (isset($_POST['save'])){
if (isset($_POST['ank_name']) && preg_match('#^([A-zА-я \-]*)$#ui', $_POST['ank_name']))
{
$user['ank_name']=$_POST['ank_name'];
mysql_query("UPDATE `user` SET `ank_name` = '".my_esc($user['ank_name'])."' WHERE `id` = '$user[id]' LIMIT 1");
}
else $err[]='Неверный формат имени';

if (isset($_POST['ank_d_r']) && (is_numeric($_POST['ank_d_r']) && $_POST['ank_d_r']>0 && $_POST['ank_d_r']<=31 || $_POST['ank_d_r']==NULL))
{
$user['ank_d_r']=$_POST['ank_d_r'];
if ($user['ank_d_r']==null)$user['ank_d_r']='null';
mysql_query("UPDATE `user` SET `ank_d_r` = $user[ank_d_r] WHERE `id` = '$user[id]' LIMIT 1");
if ($user['ank_d_r']=='null')$user['ank_d_r']=NULL;
}
else $err[]='Неверный формат дня рождения';

if (isset($_POST['ank_m_r']) && (is_numeric($_POST['ank_m_r']) && $_POST['ank_m_r']>0 && $_POST['ank_m_r']<=12 || $_POST['ank_m_r']==NULL))
{
$user['ank_m_r']=$_POST['ank_m_r'];
if ($user['ank_m_r']==null)$user['ank_m_r']='null';
mysql_query("UPDATE `user` SET `ank_m_r` = $user[ank_m_r] WHERE `id` = '$user[id]' LIMIT 1");
if ($user['ank_m_r']=='null')$user['ank_m_r']=NULL;
}
else $err[]='Неверный формат месяца рождения';

if (isset($_POST['ank_g_r']) && (is_numeric($_POST['ank_g_r']) && $_POST['ank_g_r']>0 && $_POST['ank_g_r']<=date('Y') || $_POST['ank_g_r']==NULL))
{
$user['ank_g_r']=$_POST['ank_g_r'];
if ($user['ank_g_r']==null)$user['ank_g_r']='null';
mysql_query("UPDATE `user` SET `ank_g_r` = $user[ank_g_r] WHERE `id` = '$user[id]' LIMIT 1");
if ($user['ank_g_r']=='null')$user['ank_g_r']=NULL;
}
else $err[]='Неверный формат года рождения';

if (isset($_POST['ank_city']) && preg_match('#^([A-zА-я \-]*)$#ui', $_POST['ank_city']))
{
$user['ank_city']=$_POST['ank_city'];
mysql_query("UPDATE `user` SET `ank_city` = '".my_esc($user['ank_city'])."' WHERE `id` = '$user[id]' LIMIT 1");
}
else $err[]='Неверный формат названия города';

if (isset($_POST['ank_icq']) && (is_numeric($_POST['ank_icq']) && strlen($_POST['ank_icq'])>=5 && strlen($_POST['ank_icq'])<=9 || $_POST['ank_icq']==NULL))
{
$user['ank_icq']=$_POST['ank_icq'];
if ($user['ank_icq']==null)$user['ank_icq']='null';
mysql_query("UPDATE `user` SET `ank_icq` = $user[ank_icq] WHERE `id` = '$user[id]' LIMIT 1");
if ($user['ank_icq']=='null')$user['ank_icq']=NULL;
}
else $err[]='Неверный формат ICQ';



if (isset($_POST['set_show_mail']) && $_POST['set_show_mail']==1)
{
$user['set_show_mail']=1;
mysql_query("UPDATE `user` SET `set_show_mail` = '1' WHERE `id` = '$user[id]' LIMIT 1");
}
else
{
$user['set_show_mail']=0;
mysql_query("UPDATE `user` SET `set_show_mail` = '0' WHERE `id` = '$user[id]' LIMIT 1");
}

if (isset($_POST['ank_mail']) && ($_POST['ank_mail']==null || preg_match('#^[A-z0-9-\._]+@[A-z0-9]{2,}\.[A-z]{2,4}$#ui',$_POST['ank_mail'])))
{
$user['ank_mail']=$_POST['ank_mail'];
mysql_query("UPDATE `user` SET `ank_mail` = '$user[ank_mail]' WHERE `id` = '$user[id]' LIMIT 1");
}
else $err[]='Неверный E-mail';


if (isset($_POST['ank_o_sebe']) && strlen2($_POST['ank_o_sebe'])<=512)
{

if (preg_match('#[^A-zА-я0-9 _\-\=\+\(\)\*\?\.,]#ui',$_POST['ank_o_sebe']))$err[]='В поле "О себе" используются запрещенные символы';
else {
$user['ank_o_sebe']=$_POST['ank_o_sebe'];
mysql_query("UPDATE `user` SET `ank_o_sebe` = '".my_esc($user['ank_o_sebe'])."' WHERE `id` = '$user[id]' LIMIT 1");
}
}
else $err[]='О себе нужно писать меньше :)';

if (!isset($err))msg('Изменения успешно приняты');

}
err();
aut();

echo "<form method='post' action='?$passgen'>\n";
echo "Имя в реале:<br />\n<input type='text' name='ank_name' value='".output_text($user['ank_name'],false)."' maxlength='32' /><br />\n";
echo "Дата рождения:<br />\n";
echo "<input type='text' name='ank_d_r' value='$user[ank_d_r]' size='2' maxlength='2' />\n";
echo "<input type='text' name='ank_m_r' value='$user[ank_m_r]' size='2' maxlength='2' />\n";
echo "<input type='text' name='ank_g_r' value='$user[ank_g_r]' size='4' maxlength='4' /><br />\n";
echo "Город:<br />\n<input type='text' name='ank_city' value='$user[ank_city]' maxlength='32' /><br />\n";
echo "ICQ:<br />\n<input type='text' name='ank_icq' value='$user[ank_icq]' maxlength='9' /><br />\n";
echo "E-mail:<br />\n<input type='text' name='ank_mail' value='$user[ank_mail]' maxlength='32' /><br />\n";


echo "<label><input type='checkbox' name='set_show_mail'".($user['set_show_mail']==1?' checked="checked"':null)." value='1' /> Показывать E-mail в анкете</label><br />\n";

echo "О себе:<br />\n<input type='text' name='ank_o_sebe' value='$user[ank_o_sebe]' maxlength='512' /><br />\n";
echo "<input type='submit' name='save' value='Сохранить' />\n";
echo "</form>\n";
echo "<div class='foot'>\n";
echo "&raquo;<a href='/info.php'>Посмотреть анкету</a><br />\n";


if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n";

echo "&laquo;<a href='/umenu.php'>Мое меню</a><br />\n";
echo "</div>\n";

include_once 'sys/inc/tfoot.php';
?>