<?php
include_once '../sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
only_level(2);
if (isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `katja_bot` WHERE `id` = '".intval($_GET['del'])."'"),0)!=0 && isset($user) && $user['level']>1){
mysql_query("DELETE FROM `katja_bot` WHERE `id` = '".intval($_GET['del'])."'");
header("location:?del_ok");
}
if (isset($_GET['edit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `katja_bot` WHERE `id` = '".intval($_GET['edit'])."'"),0)!=0 && isset($user) && $user['level']>1){
$edit = mysql_fetch_assoc(mysql_query("SELECT * FROM `katja_bot` WHERE `id` = '".intval($_GET['edit'])."' LIMIT 1"));
$set['title']='Изменение слов бота';
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['edit'])){
$msg=my_esc($_POST['msg']);
$otvet=my_esc($_POST['otvet']);
if (strlen2($msg)<3)$err[]="Короткое сообщение";
if (strlen2($otvet)<3)$err[]="Короткий ответ бота";
if (!isset($err)){
mysql_query("UPDATE `katja_bot` SET `msg` = '$msg', `otvet` = '$otvet' WHERE `id` = '$edit[id]'");
header("location:?edit_ok&$passgen");
}
err();
}
echo "<form method='post' action='?edit=".$edit['id']."&$passgen'><b>Сообщение</b>:<br/><textarea name='msg' style='height:35px;width:97%'>".htmlspecialchars($edit['msg'])."</textarea><br/><b>Ответ бота</b>:<br/><textarea name='otvet' style='height:35px;width:97%'>".htmlspecialchars($edit['otvet'])."</textarea><br/><input type='submit' name='edit' value='Изменить' style='width:97%'></form>";
include_once H.'sys/inc/tfoot.php';
}
$set['title']='Управление ботом Катюша';
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok'])){
$msg=my_esc($_POST['msg']);
$otvet=my_esc($_POST['otvet']);
if (strlen2($msg)<3)$err[]="Короткое сообщение";
if (strlen2($otvet)<3)$err[]="Короткий ответ";
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `katja_bot` WHERE `msg` = '$msg' AND `otvet` ='$otvet'"),0)!=0)$err[]="Такая запись уже существует";
if (!isset($err)){
mysql_query("INSERT INTO `katja_bot` (`msg`, `otvet`) values('".$msg."', '".$otvet."')");
msg("Запись успешно добавлена");
}
err();
}
if (isset($_GET['edit_ok']))msg("Запись изменена");
if (isset($_GET['del_ok']))msg("Запись удалена");
echo "<form method='post' action='?$passgen'><b>Сообщение</b>:<br/><textarea name='msg' style='height:35px;width:97%'></textarea><br/><b>Ответ бота</b>:<br/><textarea name='otvet' style='height:35px;width:97%'></textarea><br/><input type='submit' name='ok' value='Добавить' style='width:97%'></form>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `katja_bot`"),0);
if ($k_post>0)echo "<div class='foot'>Всего записей $k_post</div>";
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if($k_post==0)echo "<div class='mess'>Словарный запас бота пуст</div>";
$q=mysql_query("SELECT * FROM `katja_bot` ORDER BY `id` DESC  LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_array($q)){
if ($num == 0){
echo '<div class="nav1">';
$num = 1;
} elseif ($num == 1){
echo '<div class="nav2">';
$num = 0;
}
echo "<span style='float:right'><a href='?edit=".$post['id']."'><img src='/style/icons/settings.png' alt='edit'></a><a href='?del=".$post['id']."'><img src='/style/icons/delete.png' alt='del'></a></span><b>Сообщение</b>: ".output_text($post['msg'])."<br/><b>Ответ бота</b>: ".output_text($post['otvet'])."</div>";
}
if ($k_page>1)str('?',$k_page,$page);
include_once H.'sys/inc/tfoot.php';
?>