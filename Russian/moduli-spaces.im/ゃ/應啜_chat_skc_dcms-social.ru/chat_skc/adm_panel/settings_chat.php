<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
$temp_set=$set;
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/adm_check.php';
include_once '../sys/inc/user.php';
user_access('adm_set_chat',null,'index.php?'.SID);
adm_check();

$set['title']='Настройки чата';
include_once '../sys/inc/thead.php';
title();
if (isset($_POST['save']))
{
$temp_set['time_chat']=intval($_POST['time_chat']);
mysql_query("ALTER TABLE `user` CHANGE `set_time_chat` `set_time_chat` INT( 11 ) DEFAULT '$temp_set[time_chat]'");
$temp_set['umnik_new']=intval($_POST['umnik_new']);
$temp_set['hud_new']=intval($_POST['hud_new']);
$temp_set['anagrama_new']=intval($_POST['anagrama_new']);
$temp_set['rebus_new']=intval($_POST['rebus_new']);
$temp_set['umnik_help']=intval($_POST['umnik_help']);
$temp_set['hud_help']=intval($_POST['hud_help']);
$temp_set['anagrama_help']=intval($_POST['anagrama_help']);
$temp_set['rebus_help']=intval($_POST['rebud_help']);
$temp_set['umnik_time']=intval($_POST['umnik_time']);
$temp_set['hud_time']=intval($_POST['hud_time']);
$temp_set['anagrama_time']=intval($_POST['anagrama_time']);
$temp_set['rebus_time']=intval($_POST['rebus_time']);
$temp_set['shutnik_new']=intval($_POST['shutnik_new']);

if(preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui", $_POST['chat_shutnik']) && strlen2($_POST['chat_shutnik'])>2 && strlen2($_POST['chat_shutnik'])<=32)
$temp_set['chat_shutnik']=$_POST['chat_shutnik'];

if(preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui", $_POST['chat_umnik']) && strlen2($_POST['chat_umnik'])>2 && strlen2($_POST['chat_umnik'])<=32)
if(preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui", $_POST['chat_flirt']) && strlen2($_POST['chat_flirt'])>2 && strlen2($_POST['chat_flirt'])<=32)
$temp_set['chat_flirt']=$_POST['chat_flirt'];
if(preg_match("#^([A-zА-я0-9\-\_\])+$#ui",$_POST['chat_hud']) && strlen2($_POST['chat_hud'])>2 && strlen2($_POST['chat_hud'])<=32)
if(preg_match("#^([A-zА-я0-9\-\_\])+$#ui",$_POST['chat_anagrama']) && strlen2($_POST['chat_anagrama'])>2 && strlen2($_POST['chat_anagrama'])<=32)

if(preg_match("#^([A-zА-я0-9\-\_\])+$#ui",$_POST['chat_rebus']) && strlen2($_POST['chat_rebus'])>2 && strlen2($_POST['chat_rebus'])<=32)
$temp_set['chat_umnik']=$_POST['chat_umnik'];
$temp_set['chat_hud']=$_POST['chat_hud'];
$temp_set['chat_anagrama']=$_POST['chat_anagrama'];
$temp_set['chat_rebus']=$_POST['chat_rebus'];






if (save_settings($temp_set))
{
admin_log('Настройки','Система','Изменение параметров чата');
msg('Настройки успешно приняты');
}
else
$err='Нет прав для изменения файла настроек';
}
err();
aut();



echo "<form method=\"post\" action=\"?\">\n";

echo "Автообновление в чате:<br />\n<input type='text' name='time_chat' value='$temp_set[time_chat]' maxlength='3' /><br />\n";echo "Таймаут между вопросами (умник в чате):<br />\n<input type='text' name='umnik_new' value='$temp_set[umnik_new]' maxlength='3' /><br />\n";

echo "Таймаут между вопросами (художник в чате):<br />\n<input type='text' name='hud_new' value='$temp_set[hud_new]' maxlength='3' /><br />\n";

echo "Таймаут между вопросами (Анаграма в чате):<br />\n<input type='text' name='anagrama_new' value='$temp_set[anagrama_new]' maxlength='3' /><br />\n";
echo "Таймаут между вопросами (Ребус в чате):<br />\n<input type='text' name='rebus_new' value='$temp_set[rebus_new]' maxlength='3' /><br />\n";
echo "Таймаут между подсказками (умник в чате):<br />\n<input type='text' name='umnik_help' value='$temp_set[umnik_help]' maxlength='3' /><br />\n";
echo "Таймаут между подсказками (Художник в чате):<br />\n<input type='text' name='hud_help' value='$temp_set[hud_help]' maxlength='3' /><br />\n";
echo "Таймаут между подсказками (Анаграма в чате):<br />\n<input type='text' name='anagrama_help' value='$temp_set[anagrama_help]' maxlength='3' /><br />\n";
echo "Таймаут между подсказками (Ребус в чате):<br />\n<input type='text' name='rebus_help' value='$temp_set[rebus_help]' maxlength='3' /><br />\n";
echo "Общее время ожидание ответа (умник в чате):<br />\n<input type='text' name='umnik_time' value='$temp_set[umnik_time]' maxlength='3' /><br />\n";
echo "Общее время ожидание ответа (Художник в чате):<br />\n<input type='text' name='hud_time' value='$temp_set[hud_time]' maxlength='3' /><br />\n";
echo "Общее время ожидание ответа (Анаграма в чате):<br />\n<input type='text' name='anagrama_time' value='$temp_set[anagrama_time]' maxlength='3' /><br />\n";
echo "Общее время ожидание ответа (Ребус в чате):<br />\n<input type='text' name='rebus_time' value='$temp_set[rebus_time]' maxlength='3' /><br />\n";

echo "Таймаут между шутками (шутник в чате):<br />\n<input type='text' name='shutnik_new' value='$temp_set[shutnik_new]' maxlength='3' /><br />\n";
echo "Ник шутника:<br />\n<input type='text' name='chat_shutnik' value='$temp_set[chat_shutnik]' /><br />\n";
echo "Ник Флирт бота:<br />\n<input type='text' name='chat_flirt' value='$temp_set[chat_flirt]' /><br />\n";

echo "Ник умника:<br />\n<input type='text' name='chat_umnik' value='$temp_set[chat_umnik]' /><br />\n";
echo "Ник Художника:<br />\n<input type='text' name='chat_hud' value='$temp_set[chat_hud]' /><br />\n";
echo "Ник Анаграмы:<br />\n<input type='text' name='chat_anagrama' value='$temp_set[chat_anagrama]' /><br />\n";
echo "Ник Ребуса:<br />\n<input type='text' name='chat_rebus' value='$temp_set[chat_rebus]' /><br />\n";

echo "<input value=\"Изменить\" name='save' type=\"submit\" />\n";
echo "</form>\n";


echo "<div class='foot'>\n";
echo "&raquo;<a href='/adm_panel/chat_shut.php'>Шутки</a><br />\n";
echo "&raquo;<a href='/adm_panel/chat_flirt.php'>Фразы флирт бота</a><br />\n";
echo "&raquo;<a href='/adm_panel/chat_vopr.php'>Вопросы викторины</a><br />\n";
echo "&raquo;<a href='/adm_panel/chat_hud.php'>Вопросы художника</a><br />\n";
echo "&raquo;<a href='/adm_panel/chat_rebus.php'>Вопросы Ребуса</a><br />\n";
echo "&raquo;<a href='/adm_panel/anagrama.php'>Вопросы анаграмы</a><br />\n";
if (user_access('adm_panel_show'))
echo "&laquo;<a href='/adm_panel/'>В админку</a><br />\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>