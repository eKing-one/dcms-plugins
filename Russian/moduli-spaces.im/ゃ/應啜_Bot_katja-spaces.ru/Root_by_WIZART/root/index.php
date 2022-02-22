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
$root=get_user($set['roobot']); 
$set['title']=$root['nick'];
include_once H.'sys/inc/thead.php';
title();
aut();
mysql_query("UPDATE `user` SET `date_last` = '$time' WHERE `id` = '$root[id]'");
if (!isset($user)){
echo "<div class='mess'>Писать сообщения могут только авторизированые пользователи.</div>";
include_once H.'sys/inc/tfoot.php';
}
if (isset($_POST['ok']) && !empty($_POST['msg'])){
$msg=my_esc($_POST['msg']);
$q_msg=explode(' ', $msg);
if (strlen2($msg)>1024)$err[]='Сообщение слишком длинное';
if (strlen2($msg)<2)$err[]='Короткое сообщение';
$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `root` WHERE `id_user` = '$user[id]' AND `msg` = '".$msg."' AND `time` > '".($time-300)."' LIMIT 1"),0)!=0)$err[]="Ваше сообщение повторяет предыдущее";
if(!isset($err)){
mysql_query("INSERT INTO `root` (`id_user`,`time`,`msg`) values('$user[id]','$time','$msg')");
$roobo=mysql_query("SELECT * FROM `root_bot` WHERE `msg` like '%".$msg."%'  LIMIT 1");
if(mysql_num_rows($roobo)==0){
$rrr=rand(1,4);
if ($rrr==1)mysql_query("INSERT INTO `root` (`id_user`,`time`,`msg`, `bot`) values('".$user['id']."','".$time."','Даже не знаю что ответить ;-)', '1')");
else if ($rrr==2)mysql_query("INSERT INTO `root` (`id_user`,`time`,`msg`, `bot`) values('".$user['id']."','".$time."','Прикольно :-)', '1')");
else if ($rrr==3)mysql_query("INSERT INTO `root` (`id_user`,`time`,`msg`, `bot`) values('".$user['id']."','".$time."','С тобой так интересно  :-*', '1')");
else if ($rrr==4)mysql_query("INSERT INTO `root` (`id_user`,`time`,`msg`, `bot`) values('".$user['id']."','".$time."','Понятно :)', '1')");
}
while ($roobot=mysql_fetch_assoc($roobo)){
mysql_query("INSERT INTO `root` (`id_user`,`time`,`msg`, `bot`) values('".$user['id']."','".$time."','".$roobot['msg']."', '1')");
}
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `root_bot` WHERE `msg` = '".$msg."'"),0)==0)mysql_query("INSERT INTO `root_bot` (`msg`) values('".$msg."')");
}
}
err();
echo "<form method='post' action='?$passgen'>";
echo "<b>Сообщение</b>:<br/><textarea name='msg' style='height:35px;width:97%'></textarea>";
echo "<input type='submit' name='ok' value='Сказать' style='width:98%'></form>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `root`  WHERE `id_user` ='".$user['id']."'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q=mysql_query("SELECT * FROM `root`  WHERE `id_user` ='$user[id]' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
if ($k_post==0){
echo "<div class='mess'>Скорее напиши мне ;-) </div>";
}
while ($post = mysql_fetch_assoc($q)){
if ($num==0){	echo '<div class="nav1">';	$num=1;}elseif ($num==1){	echo '<div class="nav2">';	$num=0;}
if ($post['bot']==0)$ank=get_user($post['id_user']);
else $ank=get_user($set['roobot']);
echo " ".group($ank['id'])." <a href='/info.php?id=$ank[id]' title='Анкета $ank[nick]'>$ank[nick]</a>";
echo " ".medal($ank['id'])." ".online($ank['id'])." (".vremja($post['time']).") ";
echo "<br/>".output_text($post['msg']);
echo "</div>";
}
if ($k_page>1)str("?",$k_page,$page);
if ($user['level']>1)echo "<div class='main'><a href='adm.php'><img src='/style/icons/edit.gif' alt='W'> Управление ботом</a></div>";
include_once H.'sys/inc/tfoot.php';
?>
