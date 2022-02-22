<?php
include_once '../sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
$temp_set=$set;
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
only_level(2);
if (isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `root_bot` WHERE `id` = '".intval($_GET['del'])."'"),0)!=0 && isset($user) && $user['level']>1){
mysql_query("DELETE FROM `root_bot` WHERE `id` = '".intval($_GET['del'])."'");
header("location:?del_ok");
}
if (isset($_GET['edit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `root_bot` WHERE `id` = '".intval($_GET['edit'])."'"),0)!=0 && isset($user) && $user['level']>1){
$edit = mysql_fetch_assoc(mysql_query("SELECT * FROM `root_bot` WHERE `id` = '".intval($_GET['edit'])."' LIMIT 1"));
$set['title']='Изменение слов бота';
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['edit'])){
$msg=my_esc($_POST['msg']);
if (strlen2($msg)<3)$err[]="Короткое сообщение";
if (!isset($err)){
mysql_query("UPDATE `root_bot` SET `msg` = '$msg' WHERE `id` = '$edit[id]'");
header("location:?edit_ok&$passgen");
}
err();
}
echo "<form method='post' action='?edit=".$edit['id']."&$passgen'><b>Сообщение</b>:<br/><textarea name='msg' style='height:35px;width:97%'>".htmlspecialchars($edit['msg'])."</textarea><br/><input type='submit' name='edit' value='Изменить' style='width:97%'></form>";
include_once H.'sys/inc/tfoot.php';
}
$set['title']='Управление ботом';
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok'])){
$msg=my_esc($_POST['msg']);
if (strlen2($msg)<3)$err[]="Короткое сообщение";
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `root_bot` WHERE `msg` = '$msg'"),0)!=0)$err[]="Такая запись уже существует";
if (!isset($err)){
mysql_query("INSERT INTO `root_bot` (`msg`) values('".$msg."')");
msg("Запись успешно добавлена");
}
err();
}
if (isset($_GET['edit_ok']))msg("Запись изменена");
if (isset($_GET['del_ok']))msg("Запись удалена");
if (isset($_POST['ok_id'])){
$temp_set['roobot']=intval($_POST['roobot']); 
if (save_settings($temp_set)){
msg("id бота успешно установлено");
}
}
echo "<form method='post' action='?$passgen'><b>id Бота</b>:<br/><table><input type='text' name='roobot' value='".$set['roobot']."' style='width:47%'><input type='submit' name='ok_id' value='Изменить' style='width:47%'></table></form>";
echo "<form method='post' action='?$passgen'><b>Сообщение</b>:<br/><textarea name='msg' style='height:35px;width:97%'></textarea><br/><input type='submit' name='ok' value='Добавить' style='width:97%'></form>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `root_bot`"),0);
if ($k_post>0)echo "<div class='foot'>Всего записей $k_post</div>";
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if($k_post==0)echo "<div class='mess'>Словарный запас бота пуст</div>";
$q=mysql_query("SELECT * FROM `root_bot` ORDER BY `id` DESC  LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_array($q)){
if ($num == 0){
echo '<div class="nav1">';
$num = 1;
} elseif ($num == 1){
echo '<div class="nav2">';
$num = 0;
}
echo "<span style='float:right'><a href='?edit=".$post['id']."'><img src='/style/icons/settings.png' alt='edit'></a><a href='?del=".$post['id']."'><img src='/style/icons/delete.png' alt='del'></a></span><b>Сообщение</b>: ".output_text($post['msg'])."</div>";
}
if ($k_page>1)str('?',$k_page,$page);
include_once H.'sys/inc/tfoot.php';
?>