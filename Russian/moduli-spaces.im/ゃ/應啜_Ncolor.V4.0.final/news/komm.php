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
$set['title']='Новости - комментарии';
include_once '../sys/inc/thead.php';
title();


if (!isset($_GET['id']) && !is_numeric($_GET['id'])){header("Location: index.php?".SID);exit;}
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `news` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1",$db), 0)==0){header("Location: index.php?".SID);exit;}


if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `news_komm` WHERE `id_news` = '".intval($_GET['id'])."' AND `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){
mysql_query("INSERT INTO `news_komm` (`id_user`, `time`, `msg`, `id_news`) values('$user[id]', '$time', '".my_esc($msg)."', '".intval($_GET['id'])."')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
msg('Ваш комментарий успешно принят');
}
}

err();

aut(); // форма авторизации




$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `news_komm` WHERE `id_news` = '".intval($_GET['id'])."'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q=mysql_query("SELECT * FROM `news_komm` WHERE `id_news` = '".intval($_GET['id'])."' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет комментариев\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($post = mysql_fetch_assoc($q))
{
//$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
$ank=get_user($post['id_user']);

echo "   <tr>\n";
if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
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
echo "<a href='/info.php?id=$ank[id]'>\n";
echo GradientText("$ank[nick]", "$ank[ncolor]", "$ank[ncolor2]");
echo "</a>\n";
echo "".online($ank['id'])."<a> (".vremja($post['time']).")\n";
echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
echo output_text($post['msg'])."<br />\n";
if (isset($user) && ($user['level']>$ank['level'] || $user['level']!=0 && $user['id']==$ank['id']))
echo "<a href='delete.php?id=$post[id]'>Удалить</a><br />\n";
echo "  </td>\n";
echo "   </tr>\n";
}
echo "</table>\n";


if ($k_page>1)str("komm.php?id=".intval($_GET['id']).'&amp;',$k_page,$page); // Вывод страниц



if (isset($user))
{
echo "<form method=\"post\" name='message' action=\"?id=".intval($_GET['id'])."&amp;page=$page\">\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\"></textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input value=\"Отправить\" type=\"submit\" />\n";
echo "</form>\n";
}



echo "<a href='index.php'>К новостям</a><br />\n";
include_once '../sys/inc/tfoot.php';
?>