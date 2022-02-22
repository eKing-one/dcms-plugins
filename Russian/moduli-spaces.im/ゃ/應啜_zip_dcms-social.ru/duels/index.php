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



$set['title']='Дуэли';
title();
aut();

echo '<div class="main_menu"><img src="img/help.png"> <a href="info.php">Информация</a></div>';

if(isset($user['id']))
echo '<div class="main_menu"><img src="img/add.png"> <a href="create_duels.php">Создать дуэль</a></div>';


echo '<div class="main">Случайная дуэль:</div>';

?>

<style>
.vpravo{float:right;}
</style>

<?
$settings=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels_settings` "));

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` WHERE `active` = '1' "), 0);


if ($k_post==0)
{
echo '<div class="err">Нет активных дуэлей!</div>';
}

$q=mysql_query("SELECT * FROM `duels` WHERE `active` = '1' order by rand() limit 1 ");
while ($res = mysql_fetch_assoc($q)) {
if($res['active'] == 1){

if($res['golos1'] == $settings['golosov']){
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda']."'  where `id` =  '".$res['id_user1']."'  ");
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda2']."'  where `id` =  '".$res['id_user2']."'  ");
mysql_query("UPDATE `duels` SET `active` = '0'  where `id` =  '".$res['id']."'  ");

mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) 
values('0', '".$res['id_user1']."', 'Вы победили в дуэле [url=/duels/completed.php?duels=$res[id]][b]Открыть[/b][/url]. И получаете за победу [b]$settings[pobeda][/b] баллов', '$time')");
 
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) 
values('0', '".$res['id_user2']."', 'Вы проиграли в дуэле [url=/duels/completed.php?duels=$res[id]][b]Открыть[/b][/url]. И получаете за проигрыш [b]$settings[pobeda2][/b] баллов', '$time')");
 

}
elseif($res['golos2'] == $settings['golosov']){
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda']."'  where `id` =  '".$res['id_user2']."'  ");
mysql_query("UPDATE `user` SET `balls` = `balls`+'".$settings['pobeda2']."'  where `id` =  '".$res['id_user1']."'  ");
mysql_query("UPDATE `duels` SET `active` = '0'  where `id` =  '".$res['id']."'  ");

mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) 
values('0', '".$res['id_user2']."', 'Вы победили в дуэле [url=/duels/completed.php?duels=$res[id]][b]Открыть[/b][/url]. И получаете за победу [b]$settings[pobeda][/b] баллов', '$time')");
 
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) 
values('0', '".$res['id_user1']."', 'Вы проиграли в дуэле [url=/duels/completed.php?duels=$res[id]][b]Открыть[/b][/url]. И получаете за проигрыш [b]$settings[pobeda2][/b] баллов', '$time')");
 
}
}

$ank = get_user($res['id_user1']);

$proverka = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$res['id']."' and `id_user` = '".$user['id']."' "), 0);

$uchten = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$res['id']."' and `id_user` = '".$user['id']."' and `user` = '1' "), 0);

if(isset($user['id']) and $uchten == 0 and $res['active'] == 1 and $proverka == 0 and $res['id_user1'] != $user['id'] and $res['id_user2'] != $user['id']) { $golos = '<img src="img/golos.png"/> <a href="?duel='.$res['id'].'&user=1&token='.md5($res['time']).'"> Голосовать </a>'; }
elseif(isset($user['id']) and $uchten == 1 and $res['active'] == 1) $golos = '<img src="img/golos.png"/> Ваш голос учтен!';
else $golos = '';

echo'<div class="mess"><img src="img/top.png"/> Голосов: <b>'.$res['golos1'].'</b><span class="vpravo"><a href="/info.php?id='.$ank['id'].' "><span class="mess"><img src="img/user.png"> '.$ank['nick'].'</span></a></span>
<a href="images/'.$res['foto1'].'"><img src="images/'.$res['foto1'].'"  alt="*" class="image" align="left" height="50" width="50" style="margin: -7px 8px 1px 0px;"/>
</a><br/>'.$golos.'<br/></div>';

echo '<span class="mess"> ПРОТИВ </span>';
///////////////////////////////////////////////////////////
$ank2 = get_user($res['id_user2']);


$uchten2 = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels_golos` WHERE `id_duels` = '".$res['id']."' and `id_user` = '".$user['id']."' and `user` = '2' "), 0);


if(isset($user['id']) and $uchten2 == 0 and $res['active'] == 1 and $proverka == 0 and $res['id_user1'] != $user['id'] and $res['id_user2'] != $user['id']) { $golos2 = '<img src="img/golos.png"/> <a href="?duel='.$res['id'].'&user=2&token='.md5($res['time']).'"> Голосовать </a>'; }
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


header("Location: index.php".SID); 

}

}






$cmp = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` WHERE `active` = '0' "), 0);

$act = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` WHERE `active` = '1' "), 0);

$wait = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` WHERE `active` = '2' "), 0);


echo '<div class="main_menu"><img src="img/active.png"> <a href="active.php">Активные дуэли</a> ('.$act.')</div>';

echo '<div class="main_menu"><img src="img/wait.png"> <a href="waiting.php">Открытые дуэли</a> ('.$wait.')</div>';

echo '<div class="main_menu"><img src="img/end.png"> <a href="completed.php">Завершенные дуэли</a> ('.$cmp.')</div>';

if($user['level'] > 4) {
echo '<div class="main_menu"><img src="img/settings.gif"> <a href="settings.php">Настройки дуэлей</a> </div>';}


err();
include_once '../sys/inc/tfoot.php';
?>