<?


if (isset($_GET['act']) && isset($_GET['ok']) && $_GET['act']=='mesto' && isset($_POST['forum']) && is_numeric($_POST['forum'])
&& (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forums` WHERE `id` = '".intval($_POST['forum'])."' AND `id_gruppy`='$gruppy[id]'"),0)==1 && isset($user) && $user['id']==$gruppy['admid']
|| mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forums` WHERE `id` = '".intval($_POST['forum'])."' AND `id_gruppy`='$gruppy[id]'"),0)==1 && isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1))
{
$forum_new=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forums` WHERE `id` = '".intval($_POST['forum'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1"));

mysql_query("UPDATE `gruppy_forum_mess` SET `id_forum` = '$forum_new[id]' WHERE `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]' AND `id_them` = '$them[id]'");
mysql_query("UPDATE `gruppy_forum_thems` SET `id_forum` = '$forum_new[id]' WHERE `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]' AND `id` = '$them[id]'");
$old_forum=$forum;
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forums` WHERE `id` = '$forum_new[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1"));
$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_thems` WHERE `id_forum` = '$forum[id]' AND `id` = '$them[id]' LIMIT 1"));

msg('Тема успешно перемещена');
}





if (isset($user) && $user['id']==$gruppy['admid'] &&  isset($_GET['act']) && isset($_GET['ok']) && $_GET['act']=='delete')
{

mysql_query("DELETE FROM `gruppy_forum_thems` WHERE `id` = '$them[id]'");
mysql_query("DELETE FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]'");

msg('Тема успешно удалена');
err();
echo "<div class='menu'>\n";
echo "<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\">В форум</a><br />\n";
echo "<a href=\"forum.php\">К списку форумов</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}




if (isset($_GET['act']) && isset($_GET['ok']) && $_GET['act']=='set' && isset($_POST['name']) && isset($user) && ($user['id']==$gruppy['admid'] || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1))
{

$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (isset($_POST['translit1']) && $_POST['translit1']==1)$name=translit($name);
if (strlen2($name)<3)$err='Слишком короткое название';
if (strlen2($name)>32)$err='Слишком длинное название';
$name=my_esc($name);


if ($user['id']==$gruppy['admid']){
if (isset($_POST['up']) && $_POST['up']==1)
{
$up=1;
}
else $up=0;
$add_q=" `up` = '$up',";
}else $add_q=NULL;



if (isset($_POST['close']) && $_POST['close']==1 && $them['close']==0){
$close=1;
}
elseif ($them['close']==1 && (!isset($_POST['close']) || $_POST['close']==0))
{
$close=0;
}

else $close=$them['close'];


if (!isset($err)){
mysql_query("UPDATE `gruppy_forum_thems` SET `name` = '$name',$add_q `close` = '$close' WHERE `id` = '$them[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_thems` WHERE `id` = '$them[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1"));
$ank2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$them[id_user]' LIMIT 1"));
msg('Изменения успешно приняты');
}
}



if ((isset($user) && $user['id']==$gruppy['admid'] || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1) && isset($_GET['act']) && $_GET['act']=='post_delete' && isset($_GET['ok']))
{
foreach ($_POST as $key => $value)
{
if (ereg('^post_([0-9]*)$',$key,$postnum) && $value='1')
{
$delpost[]=$postnum[1];
}
}
if (isset($delpost) && is_array($delpost) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]'"),0)>count($delpost))
{
mysql_query("DELETE FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND (`id` = '".implode("'".' OR `id` = '."'", $delpost)."') LIMIT ".count($delpost));
if ($ank2['id']!=$user['id'])

msg('Успешно удалено '.count($delpost).' постов');
err();
echo "<div class='menu'>\n";
echo "<a href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]\">Вернуться в тему</a><br />\n";
echo "<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\">В форум</a><br />\n";
echo "<a href=\"?s=$gruppy[id]\">К списку форумов</a><br />\n";
echo "<a href=\"index.php?s=$gruppy[id]\">В сообщество</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}
else
$err='Нельзя удалить 0 или все посты из темы';
}




if (isset($_GET['act']) && $_GET['act']=='post_delete' && isset($user) && ($user['id']==$gruppy['admid'] || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1))
{
echo "<form method='post' action='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=post_delete&amp;ok'>\n";
}

?>
