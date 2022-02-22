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

$set['title']='Ответ в чате';


include_once '../sys/inc/thead.php';
title();
err();
aut(); // форма авторизации


if(!isset($user)){
echo '<div class="err">Закрыт для гостей.</div>';
}else{


include 'inc/admin_act.php';


if (isset($_GET['id']))
{
$post = mysql_fetch_array(mysql_query("SELECT * FROM `clan_chat` WHERE `id`='".intval($_GET['id'])."' LIMIT 1"));
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`=$post[id_user] LIMIT 1"));





}





if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err[]='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_chat` WHERE `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){

mysql_query("INSERT INTO `clan_chat` (id_user, time, msg) values('$user[id]', '$time', '".my_esc($msg)."')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
msg('Сообщение успешно добавлено');
header("Location: index.php".SID);
exit;




}
}


if (isset($_POST['msg']) && !isset($user) && isset($set['write_guest']) && $set['write_guest']==1 && isset($_SESSION['captcha']) && isset($_POST['chislo']))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;


if (strlen2($msg)>1024){$err='Сообщение слишком длинное';}
elseif ($_SESSION['captcha']!=$_POST['chislo']){$err='Неверное проверочное число';}
elseif (isset($_SESSION['antiflood']) && $_SESSION['antiflood']>$time-300){$err='Для того чтобы чаще писать нужно авторизоваться';}
elseif (strlen2($msg)<2){$err='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_chat` WHERE `id_user` = '0' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){
$_SESSION['antiflood']=$time;
mysql_query("INSERT INTO `guest` (id_user, time, msg) values('0', '$time', '".my_esc($msg)."')");
msg('Сообщение успешно добавлено');

}
}



if (isset($user) || (isset($set['write_guest']) && $set['write_guest']==1 && (!isset($_SESSION['antiflood']) || $_SESSION['antiflood']<$time-300)))
{
echo "<form method=\"post\" name='message' action=\"?$passgen\">\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else



echo '<b>'.$ank[nick].' ('.vremja($post['time']).')</b><br/>'.esc(trim(br(bbcode(links(stripcslashes(htmlspecialchars($post['msg']))))))).'</div>';
echo '<form method="post" action="reply.php?id='.$post['id'].'">';
echo 'Ответ:<a href="/smiles.php">[Смайлы]</a><br/><textarea name="msg">'.$ank['nick'].', </textarea><br/>';
echo '<input type="submit" value="Ответить"/>';
echo '</form>';

}}
include_once '../sys/inc/tfoot.php';
?>