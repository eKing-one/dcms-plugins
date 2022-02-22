<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

include_once '../sys/inc/thead.php';



$set['title']='Приглашение на дуэль';
title();
aut();




echo '<div class="main">Приглашение на <b>дуэль № '.$_GET['duel'].'</b></div>';

$duel=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duel'])."' "));

$settings = mysql_fetch_assoc(mysql_query("select * from `duels_settings` "));

$proverka = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` 
WHERE `id` = '".intval($_GET['duel'])."' and `id_user1` = '".$user['id']."' and `active` = '2'  "),0);

$token = md5($duel['time']);
if($_GET['token'] != $token) { header("Location: index.php?".SID); }


elseif($proverka == 0) { header("Location: index.php?".SID); }
else{

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > '".(time()-600)."'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
echo '<div class="err">Нет онлайн пользователей!</div>';
}

$q = mysql_query("SELECT `id` FROM `user` WHERE `date_last` > '".(time()-600)."' ORDER BY `date_last` DESC LIMIT $start, $set[p_str]");
while ($res = mysql_fetch_assoc($q)) {
$ank=get_user($res['id']);

$uje=mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_invite` WHERE `id_duels` = '".intval($_GET['duel'])."' 
and `id_user` = '".$user['id']."' and `user` = '".$ank['id']."' "), 0);

if($ank['id'] == $user['id']) {$pr = 'Это вы';}
elseif($uje == 1) {$pr = 'Приглашен';}
elseif($ank['duels'] == 1) {$pr = 'Запрещено';}
else {$pr = '<a href="?duel='.$_GET['duel'].'&user='.$ank['id'].'&token='.$_GET['token'].'"> Пригласить</a>';}

	echo '<div class="main_menu">'. group($ank['id']) . user::nick($ank['id'], 1, 1, 1) . ' '.$pr.'</div>';


}

if(isset($_GET['duel']) and isset($_GET['user']) and $_GET['user'] != $user['id']) {

$skolko=mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_invite` WHERE `id_duels` = '".intval($_GET['duel'])."' 
and `id_user` = '".$user['id']."' "), 0);

$uje=mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_invite` WHERE `id_duels` = '".intval($_GET['duel'])."' 
and `id_user` = '".$user['id']."' and `user` = '".intval($_GET['user'])."' "), 0);

$zapr=get_user(intval($_GET['user']));

if($uje != 0) {$err = 'Вы уже пригласили этого пользователя!';}
elseif($zapr['duels'] == 1) {$pr = 'Пользователь запретил приглашать себя в дуэли!';}
else{
if($skolko <= $settings['invite'] ) { 
$duel=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duel'])."' "));

mysql_query("INSERT INTO `duels_invite` (`id_duels`, `id_user`, `user`, `time`) 
values('".intval($_GET['duel'])."', '".$user['id']."', '".intval($_GET['user'])."', '$time')");
 

mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) 
values('0', '".intval($_GET['user'])."', '[url=/info.php?id=$user[id]][b]$user[nick][/b][/url] приглашает Вас учавствовать в дуэле [url=/duels/waiting.php?duels=$_GET[duel]][b]Открыть[/b][/url]', '$time')");
 

header("Location: ?duel=".$_GET['duel']."&token=".$_GET['token']."".SID); 
} else {
$err = 'Ошибка! Вы уже пригласили на эту дуэль '.$settings['invite'].' человек!';
}


}

}

if ($k_page>1)str("?",$k_page,$page); // Вывод страниц

err();
echo '<div class="foot"><img src="img/duels.png"> <a href="index.php">Дуэли</a></div>';
}


include_once '../sys/inc/tfoot.php';
?>