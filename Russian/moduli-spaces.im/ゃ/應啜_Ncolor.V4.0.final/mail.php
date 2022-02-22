<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
only_reg();


if ((!isset($_SESSION['refer']) || $_SESSION['refer']==NULL)
&& isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=NULL &&
!ereg('mail\.php',$_SERVER['HTTP_REFERER']))
$_SESSION['refer']=str_replace('&','&amp;',ereg_replace('^http://[^/]*/','/', $_SERVER['HTTP_REFERER']));


if (!isset($_GET['id'])){header("Location: /konts.php?".SID);exit;}
$ank=get_user($_GET['id']);
if (!$ank){header("Location: /konts.php?".SID);exit;}


$set['title']='Почта: '.$ank['nick'];
include_once 'sys/inc/thead.php';
title();



// добавляем в контакты
if ($user['add_konts']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]'"),0)==0)
mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$user[id]', '$ank[id]', '$time')");
// обновление сведений о контакте
mysql_query("UPDATE `users_konts` SET `new_msg` = '0' WHERE `id_kont` = '$ank[id]' AND `id_user` = '$user[id]' LIMIT 1");
// помечаем сообщения как прочитанные
mysql_query("UPDATE `mail` SET `read` = '1' WHERE `id_kont` = '$user[id]' AND `id_user` = '$ank[id]'");

if (isset($_POST['msg']) && $ank['id']!=0)
{

if ($user['level']==0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_kont` = '$user[id]' AND `id_user` = '$ank[id]'"), 0)==0)
{
if (!isset($_SESSION['captcha']))$err[]='Ошибка проверочного числа';
if (!isset($_POST['chislo']))$err[]='Введите проверочное число';
elseif ($_POST['chislo']==null)$err[]='Введите проверочное число';
elseif ($_POST['chislo']!=$_SESSION['captcha'])$err[]='Проверьте правильность ввода проверочного числа';
}


$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)>1024)$err[]='Сообщение превышает 1024 символа';
if (strlen2($msg)<2)$err[]='Слишком короткое сообщение';

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (!isset($err) && mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' AND `time` > '".($time-360)."' AND `msg` = '".my_esc($msg)."'"),0)==0)
{
// отправка сообщения
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$ank[id]', '".my_esc($msg)."', '$time')");
// добавляем в контакты
if ($user['add_konts']==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]'"),0)==0)
mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$user[id]', '$ank[id]', '$time')");
// обновление сведений о контакте
mysql_query("UPDATE `users_konts` SET `time` = '$time' WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]'");
msg('Сообщение успешно отправлено');
}
}

aut();
err();

echo "<table class='post'>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет сообщений\n";
echo "  </td>\n";
echo "   </tr>\n";

}
$q=mysql_query("SELECT * FROM `mail` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
echo "   <tr>\n";
if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
avatar($ank2['id']);
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/user/$ank2[pol].png' alt='' />";
echo "  </td>\n";
}


echo "  <td class='p_t'>\n";
if ($ank2){
echo "<a href=\"/info.php?id=$ank2[id]\">\n";
echo GradientText("$ank2[nick]", "$ank2[ncolor]", "$ank2[ncolor2]");
echo "</a>\n";
echo "".online($ank2['id'])."\n";
}
else
echo "[DELETED] (+$kont[count])\n";
echo "(".vremja($post['time']).")\n";
echo "  </td>\n";
echo "   </tr>\n";


echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
if ($post['read']==0)echo "(не прочитано)<br />\n";
echo output_text($post['msg'])."\n";
echo "  </td>\n";
echo "   </tr>\n";






}
echo "</table>\n";
if ($k_page>1)str("mail.php?id=$ank[id]&amp;",$k_page,$page); // Вывод страниц


if ($ank['id']!=0){


echo "<form method='post' name='message' action='/mail.php?id=$ank[id]&amp;$passgen'>\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name='msg'></textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type='checkbox' name='translit' value='1' /> Транслит</label><br />\n";
if ($user['level']==0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_kont` = '$user[id]' AND `id_user` = '$ank[id]'"), 0)==0)
echo "<img src='/captcha.php?SESS=$sess' width='100' height='30' alt='Проверочное число' /><br />\n<input name='chislo' size='5' maxlength='5' value='' type='text' /><br/>\n";
echo "<input type='submit' value='Отправить' />\n";
echo "</form>\n";



}

echo "<div class='foot'>";

if ($ank['id']!=0)
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]'"), 0)==1)
{
$kont=mysql_fetch_array(mysql_query("SELECT * FROM `users_konts` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]'"));
echo "<a href='/konts.php?type=$kont[type]&amp;act=del&amp;id=$ank[id]'>Удалить контакт из списка</a><br />\n";
}
else
{
echo "<a href='/konts.php?type=common&amp;act=add&amp;id=$ank[id]'>Добавить в список контактов</a><br />\n";
}
}

echo "<a href='/konts.php?".(isset($kont)?'type='.$kont['type']:null)."'>Список контактов</a><br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n";
echo "&laquo;<a href='umenu.php'>Мое меню</a><br />\n";
echo "</div>\n";
include_once 'sys/inc/tfoot.php';
?>