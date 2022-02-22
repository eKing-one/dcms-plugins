<?
if (isset($_GET['id']) && isset($_GET['pass']))
{
include_once H.'sys/inc/shif.php';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['id'])."' AND `pass` = '".shif($_GET['pass'])."' LIMIT 1"), 0)==1)
{
$user=get_user($_GET['id']);
//$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".intval($_GET['id'])."' AND `pass` = '".shif($_GET['pass'])."' LIMIT 1"));
$_SESSION['id_user']=$user['id'];

//setcookie('id_user', $user['id'], time()+60*60*24*365);
//setcookie('pass', cookie_encrypt($_GET['pass'],$user['id']), time()+60*60*24*365);

mysql_query("UPDATE `user` SET `date_aut` = ".time()." WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `date_last` = ".time()." WHERE `id` = '$user[id]' LIMIT 1");
}
else $err='Неправильный логин или пароль';
}
elseif (isset($_POST['nick']) && isset($_POST['pass']))
{
include_once H.'sys/inc/shif.php';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `nick` = '".my_esc($_POST['nick'])."' AND `pass` = '".shif($_POST['pass'])."' LIMIT 1"), 0)==1)
{
$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `nick` = '".my_esc($_POST['nick'])."' AND `pass` = '".shif($_POST['pass'])."' LIMIT 1"));
$_SESSION['id_user']=$user['id'];

if (isset($_POST['aut_save']) && $_POST['aut_save']==1){
setcookie('id_user', $user['id'], time()+60*60*24*365);
setcookie('pass', cookie_encrypt($_POST['pass'],$user['id']), time()+60*60*24*365);
}
mysql_query("UPDATE `user` SET `date_aut` = ".time()." WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `date_last` = ".time()." WHERE `id` = '$user[id]' LIMIT 1");
}
else $err='Неправильный логин или пароль';
}
elseif (isset($_SESSION['id_user']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = $_SESSION[id_user] LIMIT 1"), 0)==1)
{
$user=get_user($_SESSION['id_user']);
//$user=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $_SESSION[id_user] LIMIT 1"));
mysql_query("UPDATE `user` SET `date_last` = ".time()." WHERE `id` = '$user[id]' LIMIT 1");
$user['type_input']='session';
}
elseif (isset($_COOKIE['id_user']) && isset($_COOKIE['pass']) && $_COOKIE['id_user']!=NULL && $_COOKIE['pass']!=NULL)
{
include_once H.'sys/inc/shif.php';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = ".intval($_COOKIE['id_user'])." AND `pass` = '".shif(cookie_decrypt($_COOKIE['pass'],intval($_COOKIE['id_user'])))."' LIMIT 1"), 0)==1)
{
$user=get_user($_COOKIE['id_user']);
$_SESSION['id_user']=$user['id'];
mysql_query("UPDATE `user` SET `data_aut` = ".time().", `date_last` = ".time()." WHERE `id` = '$user[id]' LIMIT 1");
$user['type_input']='cookie';
}
else
{
setcookie('id_user');
setcookie('pass');
}
}

if (isset($user['activation']) && $user['activation']!=NULL) // если аккаунт не активирован
{
$err[]='Вам необходимо активировать Ваш аккаунт по ссылке, высланной на Email, указанный при регистрации';
unset($user);
}


if (isset($user))
{

$tmp_us=mysql_fetch_assoc(mysql_query("SELECT `level` FROM `user_group` WHERE `id` = '$user[group_access]' LIMIT 1"));
$user['level']=$tmp_us['level'];

// Добавление отсутствующих полей 
if (!isset($user['activation']))
mysql_query('ALTER TABLE `user` ADD `activation` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `sess`');

if (!isset($user['group_access'])) // перераспределение прав пользователей
{
// при переходе с версии ниже 6.5.1
mysql_query("ALTER TABLE `user` ADD `group_access` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `level`");
mysql_query("DROP TABLE `accesses`");
mysql_query("DROP TABLE `user_acсess`");
$q=mysql_query("SELECT `id`,`level` FROM `user` WHERE `level` != '0'");
while ($ank=mysql_fetch_assoc($q))
{

switch ($ank['level']) {
case '1':$group='3';
case '2':$group='7';
case '3':$group='8';
case '4':$group=($ank['id']=='1'?'15':'9');
}
mysql_query("UPDATE `user` SET `group_access` = '$group' WHERE `id` = '$ank[id]' LIMIT 1");


}
}

if (isset($user['type_input']) && isset($_SERVER['HTTP_REFERER']) && !eregi( str_replace('.','\.',$_SERVER['HTTP_HOST']), $_SERVER['HTTP_REFERER']) && eregi('^https?://', $_SERVER['HTTP_REFERER']) && $ref=@parse_url($_SERVER['HTTP_REFERER']))
{
if (isset($ref['host']))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user_ref` WHERE `id_user` = '$user[id]' AND `url` = '".my_esc($ref['host'])."'"), 0)==0)
mysql_query("INSERT INTO `user_ref` (`time`, `id_user`, `type_input`, `url`) VALUES ('$time', '$user[id]', '$user[type_input]', '".my_esc($ref['host'])."')");
else
mysql_query("UPDATE `user_ref` SET `time` = '$time' WHERE `id_user` = '$user[id]' AND `url` = '".my_esc($ref['host'])."'");
}


}

if (!isset($user['autorization']))
mysql_query("ALTER TABLE `user` ADD `autorization` SET( '0', '1' ) NOT NULL DEFAULT '0'");
if (!isset($user['ip_cl']))
mysql_query("ALTER TABLE `user` ADD `ip_cl` BIGINT( 20 ) NOT NULL AFTER `ip` , ADD `ip_xff` BIGINT( 20 ) NOT NULL AFTER `ip_cl`");



if ($user['set_time_chat']!=NULL)$set['time_chat']=$user['set_time_chat'];
if ($user['set_p_str']!=NULL)$set['p_str']=$user['set_p_str'];
$set['set_show_icon']=$user['set_show_icon'];

if ($webbrowser) // для web темы
{
if (is_dir(H.'style/themes/'.$user['set_them2']))$set['set_them']=$user['set_them2'];
else mysql_query("UPDATE `user` SET `set_them2` = '$set[set_them]' WHERE `id` = '$user[id]' LIMIT 1");
}
else
{
if (is_dir(H.'style/themes/'.$user['set_them']))$set['set_them']=$user['set_them'];
else mysql_query("UPDATE `user` SET `set_them` = '$set[set_them]' WHERE `id` = '$user[id]' LIMIT 1");
}

if (!isset($banpage))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `id_user` = '$user[id]' AND (`time` > '$time' OR `view` = '0')"), 0)!=0)
{
header('Location: /ban.php?'.SID);exit;
}
}




if (isset($ip2['add']))mysql_query("UPDATE `user` SET `ip` = ".ip2long($ip2['add'])." WHERE `id` = '$user[id]' LIMIT 1");
else mysql_query("UPDATE `user` SET `ip` = null WHERE `id` = '$user[id]' LIMIT 1");
if (isset($ip2['cl']))mysql_query("UPDATE `user` SET `ip_cl` = ".ip2long($ip2['cl'])." WHERE `id` = '$user[id]' LIMIT 1");
else mysql_query("UPDATE `user` SET `ip_cl` = null WHERE `id` = '$user[id]' LIMIT 1");
if (isset($ip2['xff']))mysql_query("UPDATE `user` SET `ip_xff` = ".ip2long($ip2['xff'])." WHERE `id` = '$user[id]' LIMIT 1");
else mysql_query("UPDATE `user` SET `ip_xff` = null WHERE `id` = '$user[id]' LIMIT 1");
if ($ua)mysql_query("UPDATE `user` SET `ua` = '".my_esc($ua)."' WHERE `id` = '$user[id]' LIMIT 1");


mysql_query("UPDATE `user` SET `url` = '".my_esc($_SERVER['SCRIPT_NAME'])."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `sess` = '$sess' WHERE `id` = '$user[id]' LIMIT 1");

if (isset($sess{32})){
$collision_q=mysql_query("SELECT * FROM `user` WHERE `sess` = '$sess' AND `id` <> '$user[id]'");
while ($collision = mysql_fetch_assoc($collision_q))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user_collision` WHERE `id_user` = '$user[id]' AND `id_user2` = '$collision[id]' OR `id_user2` = '$user[id]' AND `id_user` = '$collision[id]'"), 0)==0)
mysql_query("INSERT INTO `user_collision` (`id_user`, `id_user2`, `type`) values('$user[id]', '$collision[id]', 'sess')");
}
}
/*
$collision_q=mysql_query("SELECT * FROM `user` WHERE `ip` = '$iplong' AND `ua` = '".my_esc($ua)."' AND `date_last` > '".(time()-600)."' AND `id` <> '$user[id]'");
while ($collision = mysql_fetch_assoc($collision_q))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user_collision` WHERE `id_user` = '$user[id]' AND `id_user2` = '$collision[id]' OR `id_user2` = '$user[id]' AND `id_user` = '$collision[id]'"), 0)==0)
mysql_query("INSERT INTO `user_collision` (`id_user`, `id_user2`, `type`) values('$user[id]', '$collision[id]', 'ip_ua_time')");
}
*/
}
else
{
if ($webbrowser)
$set['set_them']=$set['set_them2'];
if ($ip && $ua)
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `guests` WHERE `ip` = '$iplong' AND `ua` = '".my_esc($ua)."' LIMIT 1"), 0)==1)
{
$guests=mysql_fetch_assoc(mysql_query("SELECT * FROM `guests` WHERE `ip` = '$iplong' AND `ua` = '".my_esc($ua)."' LIMIT 1"));


mysql_query("UPDATE `guests` SET `date_last` = ".time().", `url` = '".my_esc($_SERVER['SCRIPT_NAME'])."', `pereh` = '".($guests['pereh']+1)."' WHERE `ip` = '$iplong' AND `ua` = '".my_esc($ua)."' LIMIT 1");
}
else
{
mysql_query("INSERT INTO `guests` (`ip`, `ua`, `date_aut`, `date_last`, `url`) VALUES ('$iplong', '".my_esc($ua)."', '".time()."', '".time()."', '".my_esc($_SERVER['SCRIPT_NAME'])."')");
}

}
unset($access);
}


if (!isset($user) || $user['level']==0)
{
@error_reporting(0);
@ini_set('display_errors',false); // показ ошибок
if (function_exists('set_time_limit'))@set_time_limit(20); // Ставим ограничение на 20 сек
}
////////////////////////////////Рейтинг///////////////////////////////////////////////////////
mysql_query("UPDATE `user` SET `time_click`='$time' WHERE `id`='$user[id]'");
if($user['time_click']-$user['time_click_r']>30){
$akt_rating=$user['time_click_r']-$user['time_click'];
$rat_plus=$akt_rating*-0.00000008;
mysql_query("UPDATE `user` SET `time_click_r`='$time' WHERE `id`='$user[id]'");
mysql_query("UPDATE `user` SET `akt_rating`=`akt_rating`+'$rat_plus' WHERE `id`='$user[id]'");
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!isset($user) && $set['guest_select']=='1' && !isset($show_all))
{
header("Location: /aut.php");
exit;
}
?>