<?php
/*
Автор: WIZART
e-mail: bi3apt@gmail.com
icq: 617878613
Сайт: WizartWM.RU
*/
include_once '../sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==0){header("Location: index.php?".SID);exit;}
$gang = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
$set['title']="Мини-чат банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_enemies` WHERE `id_user` = '$user[id]' AND `id_gang` = '$gang[id]' LIMIT 1"),0)!=0){
$err[]="Мини чат этой банды вам не доступен так как находитесь в списке врагов этой банды.";
err();
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}

if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
$guser= mysql_fetch_array(mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang` = '$gang[id]' AND `id_user` = '".$user['id']."'"));
if ($user['id']==$guser['id_user']){
if (isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_minichat` WHERE `id_user` = '".intval($_GET['del'])."'"),0)!=0 && $guser['status']>0){
mysql_query("DELETE FROM `gangs_minichat` WHERE `id` = '".intval($_GET['del'])."'");
msg("Сообщение успешно удалено.");
}
if (isset($_POST['ok']) && isset($user)){
$msg=my_esc($_POST['msg']);
if (strlen2($msg)<2)$err[]='Короткое сообщение';
if(!isset($err)){
mysql_query("INSERT INTO `gangs_minichat` (`id_user`,`time`,`msg`,`id_gang`) values('$user[id]','$time','$msg','$gang[id]')");
}
err();
}
echo "<form method='post' action='?id=$gang[id]&$passgen'>
<b>Сообщение:</b><br/><textarea name='msg' style='height:25px;width:97%'>$otvet</textarea>
<input type='submit' name='ok' value='Сказать' style='width:98%'></form>";
} else echo "<div class='mess'>Писать в мини-чате банды могут только ее участники.</div>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_minichat` WHERE `id_gang`='".$gang['id']."'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "<div class='mess'>Мини-чат этой банды пока пуст.</div>";
$q=mysql_query("SELECT * FROM `gangs_minichat` WHERE `id_gang`='".$gang['id']."' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
$ank=get_user($post['id_user']);
if ($num==0){echo "<div class='nav1'>";	$num=1;}elseif ($num==1){echo "<div class='nav2'>";	$num=0;}
echo status($ank['id']) , group($ank['id']);
echo " <a href='/info.php?id=$ank[id]'>$ank[nick]</a> \n";
echo "".medal($ank['id'])." ".online($ank['id'])."";
echo " (<b>".vremja($post['time'])."</b>)<br/>";
echo "".output_text($post['msg'])."<br/>";
if (isset($user)){
if ($user['id']!=$ank['id'] && $user['id']==$guser['id_user'])echo "<a href='?id=$gang[id]&response=$ank[id]'> Ответить </a>";
if ($guser['status']>0)echo "<a href='?id=$gang[id]&del=$post[id]'> Удалить</a>";
}
echo "</div>";
}
if ($k_page>1)str("?id=$gang[id]&",$k_page,$page);
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
?>