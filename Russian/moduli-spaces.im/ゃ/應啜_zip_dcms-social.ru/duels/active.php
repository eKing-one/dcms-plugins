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



$set['title']='Активные дуэли';
title();
aut();





?>

<style>
.vpravo{float:right;}
</style>

<?
$settings=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels_settings` "));

if(isset($_GET['duels'])) {

$duels=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duels'])."' "));

if($duels['active'] != "1"){
header("Location: index.php?".SID); }

if($duels['active'] == 1){

if($duels['golos1'] == $settings['golosov']){
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda']."'  where `id` =  '".$duels['id_user1']."'  ");
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda2']."'  where `id` =  '".$duels['id_user2']."'  ");
mysql_query("UPDATE `duels` SET `active` = '0'  where `id` =  '".$duels['id']."'  ");

}
elseif($duels['golos2'] == $settings['golosov']){
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda']."'  where `id` =  '".$duels['id_user2']."'  ");
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda2']."'  where `id` =  '".$duels['id_user1']."'  ");
mysql_query("UPDATE `duels` SET `active` = '0'  where `id` =  '".$duels['id']."'  ");

}
}

$ank = get_user($duels['id_user1']);
echo '<div class="main">Дуэль <b>№ '.intval($_GET['duels']).'</b>';
if($user['level']>4){echo '<span class="vpravo"> <a href="?duel_delete='.intval($_GET['duels']).'"> Удалить</a></span>';}
echo '</div>';

$proverka = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$duels['id']."' and `id_user` = '".$user['id']."' "), 0);

$uchten = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$duels['id']."' and `id_user` = '".$user['id']."' and `user` = '1' "), 0);

if(isset($user['id']) and $uchten == 0 and $duels['active'] == 1 and $proverka == 0 and $duels['id_user1'] != $user['id'] and $duels['id_user2'] != $user['id']) { $golos = '<img src="img/golos.png"/> <a href="?duel='.$duels['id'].'&user=1"> Голосовать </a>'; }
elseif(isset($user['id']) and $uchten == 1 and $duels['active'] == 1) $golos = '<img src="img/golos.png"/> Ваш голос учтен!';
else $golos = '';

echo'<div class="mess"><img src="img/top.png"/> Голосов: <b>'.$duels['golos1'].'</b><span class="vpravo"><a href="/info.php?id='.$ank['id'].' "><span class="mess"><img src="img/user.png"> '.$ank['nick'].'</span></a></span>
<a href="images/'.$duels['foto1'].'"><img src="images/'.$duels['foto1'].'"  alt="*" class="image" align="left" height="50" width="50" style="margin: -7px 8px 1px 0px;"/>
</a><br/>'.$golos.'<br/></div>';

echo '<span class="mess"> ПРОТИВ </span>';
///////////////////////////////////////////////////////////
$ank2 = get_user($duels['id_user2']);


$uchten2 = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$duels['id']."' and `id_user` = '".$user['id']."' and `user` = '2' "), 0);


if(isset($user['id']) and $uchten2 == 0 and $duels['active'] == 1 and $proverka == 0 and $duels['id_user1'] != $user['id'] and $duels['id_user2'] != $user['id']) { $golos2 = '<img src="img/golos.png"/> <a href="?duel='.$duels['id'].'&user=2"> Голосовать </a>'; }
elseif(isset($user['id']) and $uchten2 == 1 and $duels['active'] == 1) $golos2 = '<img src="img/golos.png"/> Ваш голос учтен!'; 
else $golos2 = '';

echo'<div class="mess"><img src="img/top.png"/> Голосов: <b>'.$duels['golos2'].'</b><span class="vpravo"><a href="/info.php?id='.$ank2['id'].' "><span class="mess"><img src="img/user.png"> '.$ank2['nick'].'</span></a></span>
<a href="images/'.$duels['foto2'].'"><img src="images/'.$duels['foto2'].'"  alt="*" class="image" align="left" height="50" width="50" style="margin: -7px 8px 1px 0px;"/>
</a><br/>'.$golos2.'<br/></div>';

}else{
echo '<div class="main">Активные дуэли:</div>';


$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` WHERE `active` = '1' "), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
echo '<div class="err">Нет активных дуэлей!</div>';
}

$q=mysql_query("SELECT * FROM `duels` WHERE `active` = '1' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($res = mysql_fetch_assoc($q)) {
if($res['active'] == 1){

if($res['golos1'] == $settings['golosov']){
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda']."'  where `id` =  '".$res['id_user1']."'  ");
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda2']."'  where `id` =  '".$res['id_user2']."'  ");
mysql_query("UPDATE `duels` SET `active` = '0'  where `id` =  '".$res['id']."'  ");

}
elseif($res['golos2'] == $settings['golosov']){
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda']."'  where `id` =  '".$res['id_user2']."'  ");
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda2']."'  where `id` =  '".$res['id_user1']."'  ");
mysql_query("UPDATE `duels` SET `active` = '0'  where `id` =  '".$res['id']."'  ");

}
}

$ank = get_user($res['id_user1']);
echo '<div class="main">Дуэль <b>№ '.$res['id'].'</b>';
if($user['level']>4){echo '<span class="vpravo"> <a href="?duel_delete='.$res['id'].'"> Удалить</a></span>';}
echo '</div>';
$proverka = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$res['id']."' and `id_user` = '".$user['id']."' "), 0);

$uchten = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$res['id']."' and `id_user` = '".$user['id']."' and `user` = '1' "), 0);

if(isset($user['id']) and $uchten == 0 and $res['active'] == 1 and $proverka == 0 and $res['id_user1'] != $user['id'] and $res['id_user2'] != $user['id']) { $golos = '<img src="img/golos.png"/> <a href="?duel='.$res['id'].'&user=1"> Голосовать </a>'; }
elseif(isset($user['id']) and $uchten == 1 and $res['active'] == 1) $golos = '<img src="img/golos.png"/> Ваш голос учтен!';
else $golos = '';

echo'<div class="mess"><img src="img/top.png"/> Голосов: <b>'.$res['golos1'].'</b><span class="vpravo"><a href="/info.php?id='.$ank['id'].' "><span class="mess"><img src="img/user.png"> '.$ank['nick'].'</span></a></span>
<a href="images/'.$res['foto1'].'"><img src="images/'.$res['foto1'].'"  alt="*" class="image" align="left" height="50" width="50" style="margin: -7px 8px 1px 0px;"/>
</a><br/>'.$golos.'<br/></div>';

echo '<span class="mess"> ПРОТИВ </span>';
///////////////////////////////////////////////////////////
$ank2 = get_user($res['id_user2']);


$uchten2 = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$res['id']."' and `id_user` = '".$user['id']."' and `user` = '2' "), 0);


if(isset($user['id']) and $uchten2 == 0 and $res['active'] == 1 and $proverka == 0 and $res['id_user1'] != $user['id'] and $res['id_user2'] != $user['id']) { $golos2 = '<img src="img/golos.png"/> <a href="?duel='.$res['id'].'&user=2"> Голосовать </a>'; }
elseif(isset($user['id']) and $uchten2 == 1 and $res['active'] == 1) $golos2 = '<img src="img/golos.png"/> Ваш голос учтен!'; 
else $golos2 = '';

echo'<div class="mess"><img src="img/top.png"/> Голосов: <b>'.$res['golos2'].'</b><span class="vpravo"><a href="/info.php?id='.$ank2['id'].' "><span class="mess"><img src="img/user.png"> '.$ank2['nick'].'</span></a></span>
<a href="images/'.$res['foto2'].'"><img src="images/'.$res['foto2'].'"  alt="*" class="image" align="left" height="50" width="50" style="margin: -7px 8px 1px 0px;"/>
</a><br/>'.$golos2.'<br/></div>';
}

if(isset($_GET['duel']) and ($_GET['user'] == 1 or $_GET['user'] == 2)) {

$proverka = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".intval($_GET['duel'])."' and `id_user` = '".$user['id']."' "), 0);
$mail=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duel'])."' "));

if(isset($user['id']) and $proverka == 0 and $mail['id_user1'] != $user['id'] and $mail['id_user2'] != $user['id']) {

mysql_query("INSERT INTO `duels_golos` (`id_duels`, `user`, `id_user`, `time` ) 
values('".intval($_GET['duel'])."',  '".intval($_GET['user'])."' , '".$user['id']."', '".$time."' )");

$vsego = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".intval($_GET['duel'])."' and `user` =  '".intval($_GET['user'])."' "),0);

mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['golos']."'  where `id` =  '".$user['id']."'  ");

if($_GET['user'] == 1) {
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) 
values('0', '".intval($mail['id_user1'])."', 'За ваше фото проголосовали в дуэле  [url=/duels/active.php?duels=$_GET[duel]][b]Открыть[/b][/url]', '$time')");
 
mysql_query("UPDATE `duels` SET `golos1` = '".$vsego."' where `id` =  '".intval($_GET['duel'])."'  ");

}else{ 

mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) 
values('0', '".intval($mail['id_user2'])."', 'За ваше фото проголосовали в дуэле  [url=/duels/active.php?duels=$_GET[duel]][b]Открыть[/b][/url]', '$time')");

mysql_query("UPDATE `duels` SET `golos2` = '".$vsego."' where `id` =  '".intval($_GET['duel'])."'  ");
}

header("Location: active.php".SID); 

}

}

if(isset($_GET['duel_delete']) and $user['level'] > 4) {

$del=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duel_delete'])."' "));

$path = 'images/'.$del['foto1'];
if (file_exists($path)) unlink($path);

$path2 = 'images/'.$del['foto2'];
if (file_exists($path2)) unlink($path2);

mysql_query("DELETE FROM `duels` WHERE `id` = '".intval($_GET['duel_delete'])."' ");
mysql_query("DELETE FROM `duels_golos` WHERE `id_duels` = '".intval($_GET['duel_delete'])."' ");
mysql_query("DELETE FROM `duels_invite` WHERE `id_duels` = '".intval($_GET['duel_delete'])."' ");



header("Location: active.php".SID); 
}


if ($k_page>1)str("?",$k_page,$page); // Вывод страниц

}
echo '<div class="foot"><img src="img/duels.png"> <a href="index.php">Дуэли</a></div>';


err();
include_once '../sys/inc/tfoot.php';
?>