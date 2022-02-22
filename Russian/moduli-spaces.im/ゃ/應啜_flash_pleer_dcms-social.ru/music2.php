<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
$set['title']='Импорт';
include_once 'sys/inc/thead.php';
title();
aut();

if (isset($_GET['set']))
{
if (isset($_POST['music']) && strlen2(mysql_real_escape_string(esc(stripcslashes(htmlspecialchars($_POST['music'])))))<=84)
{
$user['music']=mysql_real_escape_string(esc(stripcslashes(htmlspecialchars($_POST['music']))));
mysql_query("UPDATE `user` SET `music` = '$user[music]' WHERE `id` = '$user[id]' LIMIT 1");
}
else $err='Ошибка';
if (!isset($err))msg('Музыка успешно изменена');
}
echo'URL мелодии через http://:';
echo "<form method='post' action='music2.php?p=music&amp;set'>\n";

echo "<input type='music' name='music' value='$user[music]' maxlength='83' />\n";
echo "<input type='submit' name='save' value='Изменить' />\n";
echo "</form>\n";

include_once 'sys/inc/tfoot.php';
?>
