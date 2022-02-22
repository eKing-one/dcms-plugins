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


$set['title']='Гостевая книга'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();


include 'inc/admin_act.php';

if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err[]='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `guest` WHERE `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){

mysql_query("INSERT INTO `guest` (id_user, time, msg) values('$user[id]', '$time', '".my_esc($msg)."')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
msg('Сообщение успешно добавлено');
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
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `guest` WHERE `id_user` = '0' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){
$_SESSION['antiflood']=$time;
mysql_query("INSERT INTO `guest` (id_user, time, msg) values('0', '$time', '".my_esc($msg)."')");
msg('Сообщение успешно добавлено');
}
}


err();
aut(); // форма авторизации
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `guest`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет сообщений\n";
echo "  </td>\n";
echo "   </tr>\n";

}

$q=mysql_query("SELECT * FROM `guest` ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{

if ($post['id_user']==0)
{
$ank['id']=0;
$ank['pol']='guest';
$ank['level']=0;
}
else
$ank=get_user($post['id_user']);
//$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
echo "   <tr>\n";
if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
if ($ank['id']==0)
echo "<img src='/sys/avatar/guest.png' alt='Гость' />";
else
avatar($ank['id']);
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/user/$ank[pol].png' alt='' />";
echo "  </td>\n";
}



echo "  <td class='p_t'>\n";
if ($ank['id']==0)
echo "Гость (".vremja($post['time']).")\n";
else
{
echo "<a href='/info.php?id=$ank[id]'>\n";
echo GradientText("$ank[nick]", "$ank[ncolor]", "$ank[ncolor2]");
echo "</a>\n";
echo "".online($ank['id'])."<a> (".vremja($post['time']).")\n";
}
echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
echo output_text($post['msg'])."<br />\n";
if (user_access('guest_delete'))
echo "<a href='delete.php?id=$post[id]'>Удалить</a><br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";




if ($k_page>1)str('?',$k_page,$page); // Вывод страниц

if (isset($user) || (isset($set['write_guest']) && $set['write_guest']==1 && (!isset($_SESSION['antiflood']) || $_SESSION['antiflood']<$time-300)))
{
echo "<form method=\"post\" name='message' action=\"?$passgen\">\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\"></textarea><br />\n";


if (isset($user) && $user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
if (!isset($user))echo "<img src='/captcha.php?SESS=$sess' width='100' height='30' alt='Проверочное число' /><br />\n<input name='chislo' size='5' maxlength='5' value='' type='text' /><br/>\n";
echo "<input value=\"Отправить\" type=\"submit\" />\n";
echo "</form>\n";
}
elseif(isset($set['write_guest']) && $set['write_guest']==1 && isset($_SESSION['antiflood']) && $_SESSION['antiflood']>$time-300)
{
echo "<div class='foot'>\n";
echo "* Гостем вы можете писать только по 1 сообщению в 5 минут<br />\n";
echo "</div>\n";
}



include 'inc/admin_form.php';
include_once '../sys/inc/tfoot.php';
?>
