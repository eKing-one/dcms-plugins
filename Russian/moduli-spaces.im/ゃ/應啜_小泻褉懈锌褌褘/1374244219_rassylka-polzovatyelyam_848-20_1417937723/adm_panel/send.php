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
include_once '../sys/inc/user.php';
only_level(4);

$set['title']='Массовая рассылка';
include_once '../sys/inc/thead.php';
title();

if (isset($_POST['save']) && isset($_POST['msg']))
{

if($_POST['to'] == 'mod'){
$filter =  "AND `group_access`>'1'";
$too=2;
} else if($_POST['to'] == 'adm'){
$filter =  "AND `group_access`>'7'";
$too=8;
} else $filter = '';

$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)>1024)$err='Сообщение превышает 1024 символа';
if (strlen2($msg)<2)$err='Слишком короткое сообщение';
if (!isset($err))
{

mysql_query("INSERT INTO `rassilka_send` (`msg`, `time`, `group_access`) values('$msg', '$time', '". $too ."')");


$q=mysql_query("SELECT * FROM `user` WHERE `group_access` >= '$too'");
while ($array=mysql_fetch_array($q)){
$update=$array['new_news_read']+1;
mysql_query("UPDATE `user` SET `new_news_read` = '$update' WHERE `id`='$array[id]' LIMIT 1");
}
msg('Сообщения успешно отправлены');
include_once '../sys/inc/tfoot.php';
}



}
err();

$count=mysql_result(mysql_query("SELECT COUNT(*) FROM `user`"),0);
echo'Всего пользователей: <b>'.$count.'</b><br/>';
echo "<form method=\"post\" action=\"?\">\n";
echo "Сообщение:<br />\n<textarea name=\"msg\"></textarea><br />\n";
if ($user['set_translit']==1)echo "<input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит<br />\n";
echo 'Кому: <select name="to">
<option value="">Всем</option>
<option value="mod">Модерам</option>
<option value="adm">Админам</option>
</select><br/>';
echo "<input value=\"Отправить\" name='save' type=\"submit\" />\n";
echo "</form>\n";








echo "<div class='foot'>\n";
echo "&laquo;<a href='/adm_panel/'>В админку</a><br />\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>