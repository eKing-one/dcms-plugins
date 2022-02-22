<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Доступность'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if($ank['id']==$user['id'] && isset($user))
{
if(isset($_POST['submited']))
{
if(isset($_POST['adult']) && $_POST['adult']==1)$comm['adult']=1;else $comm['adult']=0;
mysql_query("UPDATE `comm` SET `adult` = '$comm[adult]' WHERE `id` = '$comm[id]'");
if(isset($_POST['chat']) && $_POST['chat']==1)$comm['chat']=1;else $comm['chat']=0;
mysql_query("UPDATE `comm` SET `chat` = '$comm[chat]' WHERE `id` = '$comm[id]'");
if(isset($_POST['forum']) && $_POST['forum']==1)$comm['forum']=1;else $comm['forum']=0;
mysql_query("UPDATE `comm` SET `forum` = '$comm[forum]' WHERE `id` = '$comm[id]'");
if(isset($_POST['files']) && $_POST['files']==1)$comm['files']=1;else $comm['files']=0;
mysql_query("UPDATE `comm` SET `files` = '$comm[files]' WHERE `id` = '$comm[id]'");
if($_POST['join_rule']==1)$comm['join_rule']=1;elseif($_POST['join_rule']==2)$comm['join_rule']=2;else $comm['join_rule']=3;
mysql_query("UPDATE `comm` SET `join_rule` = '$comm[join_rule]' WHERE `id` = '$comm[id]'");
if($_POST['read_rule']==1)$comm['read_rule']=1;else $comm['read_rule']=2;
mysql_query("UPDATE `comm` SET `read_rule` = '$comm[read_rule]' WHERE `id` = '$comm[id]'");
if($_POST['write_rule']==1)$comm['write_rule']=1;else $comm['write_rule']=2;
mysql_query("UPDATE `comm` SET `write_rule` = '$comm[write_rule]' WHERE `id` = '$comm[id]'");
if($_POST['chat_rule']==1)$comm['chat_rule']=1;else $comm['chat_rule']=2;
mysql_query("UPDATE `comm` SET `chat_rule` = '$comm[chat_rule]' WHERE `id` = '$comm[id]'");
if(!isset($err))
{
msg("Изменения сохранены");
}
}

err();

echo "<form method='post'>";
echo "<input type='checkbox' name='adult' value='1'".($comm['adult']==1?" checked='checked'":null)."> Только для взрослых(18+)<br/>";
echo "<span style='font-size:small;font-weight:bold'>Разделы сообщества</span><br/>";
echo "<input type='checkbox' name='chat' value='1'".($comm['chat']==1?" checked='checked'":null)."> Чат<br/>\n";
echo "<input type='checkbox' name='forum' value='1'".($comm['forum']==1?" checked='checked'":null)."> Форум<br/>\n";
echo "<input type='checkbox' name='files' value='1'".($comm['files']==1?" checked='checked'":null)."> Файлы<br//>";
echo "<span style='font-size:small;font-weight:bold'>Членство</span><br/>";
echo "<input type='radio' name='join_rule' value='1'".($comm['join_rule']==1?" checked='checked'":null).">Свободное<br/>\n";
echo "<input type='radio' name='join_rule' value='2'".($comm['join_rule']==2?" checked='checked'":null).">Через модератора<br/>\n";
echo "<input type='radio' name='join_rule' value='3'".($comm['join_rule']==3?" checked='checked'":null).">По приглашениям<br/>";
echo "<span style='font-size:small;font-weight:bold'>Настройки форума</span><br/><span style='font-size:small;color:#209143'>Читатели:</span><br/>";
echo "<input type='radio' name='read_rule' value='1'".($comm['read_rule']==1?" checked='checked'":null).">Свободное<br/>\n";
echo "<input type='radio' name='read_rule' value='2'".($comm['read_rule']==2?" checked='checked'":null).">Только участники<br/>";
echo "<span style='font-size:small;color:#209143'>Писатели:</span><br/>";
echo "<input type='radio' name='write_rule' value='1'".($comm['write_rule']==1?" checked='checked'":null).">Свободное<br/>\n";
echo "<input type='radio' name='write_rule' value='2'".($comm['write_rule']==2?" checked='checked'":null).">Только участники";
echo "<br/><span style='font-size:small;font-weight:bold'>Настройки чата</span><br/>";
echo "<input type='radio' name='chat_rule' value='1'".($comm['chat_rule']==1?" checked='checked'":null).">Открыт для всех<br/>\n";
echo "<input type='radio' name='chat_rule' value='2'".($comm['chat_rule']==2?" checked='checked'":null).">Только участники<br/>";
echo "<input name='submited' type='submit' value='Сохранить'> <a href='?act=comm_settings&id=$comm[id]'>Назад</a></form>";
echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>";
}
else{header("Location:/comm");exit;}
}
else{header("Location:/comm");exit;}
?>