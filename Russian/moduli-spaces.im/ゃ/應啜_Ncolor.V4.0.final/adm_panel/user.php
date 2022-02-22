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


if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]' LIMIT 1"),0)==0){header("Location: /index.php?".SID);exit;}


$ank=get_user($ank['id']);
if ($user['level']<=$ank['level']){header("Location: /index.php?".SID);exit;}


$set['title']='Профиль пользователя '.$ank['nick'];
include_once '../sys/inc/thead.php';
title();


if (isset($_POST['save'])){
if (isset($_POST['ncolor']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['ncolor']))))<=10)
{
$ank['ncolor']=esc(stripcslashes(htmlspecialchars($_POST['ncolor'])));
mysql_query("UPDATE `user` SET `ncolor` = '$ank[ncolor]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Начало цвета не больше 10символов!';
if (isset($_POST['ncolor2']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['ncolor2']))))<=10)
{
$ank['ncolor2']=esc(stripcslashes(htmlspecialchars($_POST['ncolor2'])));
mysql_query("UPDATE `user` SET `ncolor2` = '$ank[ncolor2]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Конец цвета не больше 10символов!';

if (isset($_POST['nick']) && $_POST['nick']!=$ank['nick'])
{

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `nick` = '".my_esc($_POST['nick'])."'"),0)==1)
$err='Ник '.$_POST['nick'].' уже занят';
elseif (user_access('user_change_nick'))
{
$nick=my_esc($_POST['nick']);
if( !preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui", $nick))$err[]='В нике присутствуют запрещенные символы';
if (strlen2($nick)<3)$err[]='Короткий ник';
if (strlen2($nick)>32)$err[]='Длина ника превышает 32 символа';
if (!isset($err))
{
admin_log('Пользователи','Изменение ника',"Ник $ank[nick] изменен на $nick");
$ank['nick']=$nick;
mysql_query("UPDATE `user` SET `nick` = '$nick' WHERE `id` = '$ank[id]' LIMIT 1");
}
}
else $err[]='У Вас нет привилегий на изменение ника пользователя';



}



if (isset($_POST['set_show_icon']) && ($_POST['set_show_icon']==1 || $_POST['set_show_icon']==0))
{
$ank['set_show_icon']=$_POST['set_show_icon'];
mysql_query("UPDATE `user` SET `set_show_icon` = '$ank[set_show_icon]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка режима аватара';

if (isset($_POST['set_translit']) && ($_POST['set_translit']==1 || $_POST['set_translit']==0))
{
$ank['set_translit']=$_POST['set_translit'];
mysql_query("UPDATE `user` SET `set_translit` = '$ank[set_translit]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка режима транслита';

if (isset($_POST['set_files']) && ($_POST['set_files']==1 || $_POST['set_files']==0))
{
$ank['set_files']=$_POST['set_files'];
mysql_query("UPDATE `user` SET `set_files` = '$ank[set_files]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка режима файлов';

if (isset($_POST['set_time_chat']) && (is_numeric($_POST['set_time_chat']) && $_POST['set_time_chat']>=0 && $_POST['set_time_chat']<=900))
{
$ank['set_time_chat']=$_POST['set_time_chat'];
mysql_query("UPDATE `user` SET `set_time_chat` = '$ank[set_time_chat]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Ошибка во времени автообновления';

if (isset($_POST['set_p_str']) && (is_numeric($_POST['set_p_str']) && $_POST['set_p_str']>0 && $_POST['set_p_str']<=100))
{
$ank['set_p_str']=$_POST['set_p_str'];
mysql_query("UPDATE `user` SET `set_p_str` = '$ank[set_p_str]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Неправильное количество пунктов на страницу';




if (isset($_POST['ank_name']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['ank_name']))))<=32)
{
$ank['ank_name']=esc(stripcslashes(htmlspecialchars($_POST['ank_name'])));
mysql_query("UPDATE `user` SET `ank_name` = '$ank[ank_name]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Имя не может быть длиннее 32 символов';

if (isset($_POST['ank_d_r']) && (is_numeric($_POST['ank_d_r']) && $_POST['ank_d_r']>0 && $_POST['ank_d_r']<=31 || $_POST['ank_d_r']==NULL))
{
$ank['ank_d_r']=$_POST['ank_d_r'];
if ($ank['ank_d_r']==null)$ank['ank_d_r']='null';
mysql_query("UPDATE `user` SET `ank_d_r` = $ank[ank_d_r] WHERE `id` = '$ank[id]' LIMIT 1");
if ($ank['ank_d_r']=='null')$ank['ank_d_r']=NULL;
}
else $err='Неверный формат дня рождения';

if (isset($_POST['ank_m_r']) && (is_numeric($_POST['ank_m_r']) && $_POST['ank_m_r']>0 && $_POST['ank_m_r']<=12 || $_POST['ank_m_r']==NULL))
{
$ank['ank_m_r']=$_POST['ank_m_r'];
if ($ank['ank_m_r']==null)$ank['ank_m_r']='null';
mysql_query("UPDATE `user` SET `ank_m_r` = $ank[ank_m_r] WHERE `id` = '$ank[id]' LIMIT 1");
if ($ank['ank_m_r']=='null')$ank['ank_m_r']=NULL;
}
else $err='Неверный формат месяца рождения';

if (isset($_POST['ank_g_r']) && (is_numeric($_POST['ank_g_r']) && $_POST['ank_g_r']>0 && $_POST['ank_g_r']<=date('Y') || $_POST['ank_g_r']==NULL))
{
$ank['ank_g_r']=$_POST['ank_g_r'];
if ($ank['ank_g_r']==null)$ank['ank_g_r']='null';
mysql_query("UPDATE `user` SET `ank_g_r` = $ank[ank_g_r] WHERE `id` = '$ank[id]' LIMIT 1");
if ($ank['ank_g_r']=='null')$ank['ank_g_r']=NULL;
}
else $err='Неверный формат года рождения';

if (isset($_POST['ank_city']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['ank_city']))))<=32)
{
$ank['ank_city']=esc(stripcslashes(htmlspecialchars($_POST['ank_city'])));
mysql_query("UPDATE `user` SET `ank_city` = '$ank[ank_city]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Название города не может быть длиннее 32 символов';

if (isset($_POST['ank_icq']) && (is_numeric($_POST['ank_icq']) && strlen($_POST['ank_icq'])>=5 && strlen($_POST['ank_icq'])<=9 || $_POST['ank_icq']==NULL))
{
$ank['ank_icq']=$_POST['ank_icq'];
if ($ank['ank_icq']==null)$ank['ank_icq']='null';
mysql_query("UPDATE `user` SET `ank_icq` = $ank[ank_icq] WHERE `id` = '$ank[id]' LIMIT 1");
if ($ank['ank_icq']=='null')$ank['ank_icq']=NULL;
}
else $err='Неверный формат ICQ';

if (isset($_POST['ank_n_tel']) && (is_numeric($_POST['ank_n_tel']) && strlen($_POST['ank_n_tel'])>=5 && strlen($_POST['ank_n_tel'])<=11 || $_POST['ank_n_tel']==NULL))
{
$ank['ank_n_tel']=$_POST['ank_n_tel'];
mysql_query("UPDATE `user` SET `ank_n_tel` = '$ank[ank_n_tel]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Неверный формат номера телефона';

if (isset($_POST['ank_mail']) && (eregi('^[^@]*@[^@]*\.[^@]*$',$_POST['ank_mail']) || $_POST['ank_mail']==NULL))
{
$ank['ank_mail']=esc($_POST['ank_mail']);
mysql_query("UPDATE `user` SET `ank_mail` = '$ank[ank_mail]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='Неверный E-mail';


if (isset($_POST['ank_o_sebe']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['ank_o_sebe']))))<=512)
{
$ank['ank_o_sebe']=esc(stripcslashes(htmlspecialchars($_POST['ank_o_sebe'])));
mysql_query("UPDATE `user` SET `ank_o_sebe` = '$ank[ank_o_sebe]' WHERE `id` = '$ank[id]' LIMIT 1");
}
else $err='О себе нужно писать меньше :)';


if (isset($_POST['new_pass']) && strlen2($_POST['new_pass'])>5)
{
admin_log('Пользователи','Смена пароля',"Пользователю '$ank[nick]' установлен новый пароль");
mysql_query("UPDATE `user` SET `pass` = '".shif($_POST['new_pass'])."' WHERE `id` = '$ank[id]' LIMIT 1");
}



if (user_access('user_change_group') && isset($_POST['group_access']))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user_group` WHERE `id` = '".intval($_POST['group_access'])."' AND `level` < '$user[level]'"),0)==1)
{
if ($ank['group_access']!=intval($_POST['group_access']))
{
admin_log('Пользователи','Изменение статуса',"Пользователь '$ank[nick]': Статус '$ank[group_name]' изменен на '".mysql_result(mysql_query("SELECT `name` FROM `user_group` WHERE `id` = '".intval($_POST['group_access'])."'"),0)."'");
$ank['group_access']=intval($_POST['group_access']);
mysql_query("UPDATE `user` SET `group_access` = '$ank[group_access]' WHERE `id` = '$ank[id]' LIMIT 1");
}
}
}




if (($user['level']>=3 || $ank['id']==$user['id']) && isset($_POST['balls']) && is_numeric($_POST['balls'])){
$ank['balls']=intval($_POST['balls']);
mysql_query("UPDATE `user` SET `balls` = '$ank[balls]' WHERE `id` = '$ank[id]' LIMIT 1");}



admin_log('Пользователи','Профиль',"Редактирование профиля пользователя '$ank[nick]' (id#$ank[id])");

if (!isset($err))msg('Изменения успешно приняты');

}
err();
aut();

echo "<form method='post' action='user.php?id=$ank[id]'>\n";

echo "Ник:<br />\n<input".(user_access('user_change_nick')?null:' disabled="disabled"')." type='text' name='nick' value='$ank[nick]' maxlength='32' /><br />\n";
echo "Имя в реале:<br />\n<input type='text' name='ank_name' value='$ank[ank_name]' maxlength='32' /><br />\n";
echo "Дата рождения:<br />\n";
echo "<input type='text' name='ank_d_r' value='$ank[ank_d_r]' size='2' maxlength='2' />\n";
echo "<input type='text' name='ank_m_r' value='$ank[ank_m_r]' size='2' maxlength='2' />\n";
echo "<input type='text' name='ank_g_r' value='$ank[ank_g_r]' size='4' maxlength='4' /><br />\n";
echo "Город:<br />\n<input type='text' name='ank_city' value='$ank[ank_city]' maxlength='32' /><br />\n";
echo "ICQ:<br />\n<input type='text' name='ank_icq' value='$ank[ank_icq]' maxlength='9' /><br />\n";
echo "E-mail:<br />\n<input type='text' name='ank_mail' value='$ank[ank_mail]' maxlength='32' /><br />\n";
echo "Номер телефона:<br />\n<input type='text' name='ank_n_tel' value='$ank[ank_n_tel]' maxlength='11' /><br />\n";
echo "О себе:<br />\n<input type='text' name='ank_o_sebe' value='$ank[ank_o_sebe]' maxlength='512' /><br />\n";



echo "Автообновление в чате:<br />\n<input type='text' name='set_time_chat' value='$ank[set_time_chat]' maxlength='3' /><br />\n";
echo "Пунктов на страницу:<br />\n<input type='text' name='set_p_str' value='$ank[set_p_str]' maxlength='3' /><br />\n";

echo "Иконки:<br />\n<select name=\"set_show_icon\">\n";
if ($ank['set_show_icon']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>Показывать</option>\n";
if ($ank['set_show_icon']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Скрывать</option>\n";
echo "</select><br />\n";

echo "Транслит:<br />\n<select name=\"set_translit\">\n";
if ($ank['set_translit']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>По выбору</option>\n";
if ($ank['set_translit']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Никогда</option>\n";
echo "</select><br />\n";

echo "Выгрузка файлов:<br />\n<select name=\"set_files\">\n";
if ($ank['set_files']==1)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"1\"$sel>Показывать поле</option>\n";
if ($ank['set_files']==0)$sel=' selected="selected"';else $sel=NULL;
echo "<option value=\"0\"$sel>Не использовать выгрузку</option>\n";
echo "</select><br />\n";

if ($user['level']<3)$dis=' disabled="disabled"';else $dis=NULL;
echo "Баллы:<br />\n<input type='text'$dis name='balls' value='$ank[balls]' /><br />\n";
echo "Начало цвета ника:<br />\n<input type='text' name='ncolor' value='$ank[ncollor]' maxlength='10' /><br />\n";
echo "Конец цвета ника:<br />\n<input type='text' name='ncolor2' value='$ank[ncollor2]' maxlength='10' /><br />\n";

echo "Группа:<br />\n<select name='group_access'".(user_access('user_change_group')?null:' disabled="disabled"')."><br />\n";

$q=mysql_query("SELECT * FROM `user_group` ORDER BY `level`,`id` ASC");
while ($post = mysql_fetch_assoc($q))
{
echo "<option value='$post[id]'".($post['level']>=$user['level']?" disabled='disabled'":null)."".($post['id']==$ank['group_access']?" selected='selected'":null).">".$post['name']."</option>\n";
}

echo "</select><br />\n";

echo "Новый пароль:<br />\n<input type='text' name='new_pass' value='' /><br />\n";



echo "<input type='submit' name='save' value='Сохранить' />\n";
echo "</form>\n";


echo "<div class='foot'>\n";
echo "&raquo;<a href=\"/mail.php?id=$ank[id]\">Написать сообщение</a><br />\n";
echo "&laquo;<a href=\"/info.php?id=$ank[id]\">В анкету</a><br />\n";
if (user_access('adm_panel_show'))
echo "&laquo;<a href='/adm_panel/'>В админку</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>