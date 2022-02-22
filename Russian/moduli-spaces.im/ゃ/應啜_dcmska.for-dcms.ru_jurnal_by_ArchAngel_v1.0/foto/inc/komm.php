<?
if (!isset($user) && !isset($_GET['id_user'])){header("Location: /foto/?".SID);exit;}
if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id_user']))$ank['id']=intval($_GET['id_user']);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]' LIMIT 1"),0)==0){header("Location: /foto/?".SID);exit;}
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $ank[id] LIMIT 1"));

$gallery['id']=intval($_GET['id_gallery']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery` WHERE `id` = '$gallery[id]' AND `id_user` = '$ank[id]' LIMIT 1"),0)==0){header("Location: /foto/$ank[id]/?".SID);exit;}
$gallery=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery` WHERE `id` = '$gallery[id]' AND `id_user` = '$ank[id]' LIMIT 1"));

$foto['id']=intval($_GET['id_foto']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id` = '$foto[id]' LIMIT 1"),0)==0){header("Location: /foto/$ank[id]/$gallery[id]/?".SID);exit;}
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = '$foto[id]'  LIMIT 1"));

$set['title']=$ank['nick'].' - '.$gallery['name'].' - '.$foto['name'].' - Комментари'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();




if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_komm` WHERE `id_foto` = '$foto[id]' AND `id_user` = '$user[id]' AND `msg` = '".mysql_escape_string($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){

if ($ank['id']!=$user['id'])mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`, `type`) values('0', '$ank[id]', '$user[nick] оставил [url=/foto/$ank[id]/$gallery[id]/komm/$foto[id]/]комментарий к вашему фото[/url]', '$time', 'foto')"); 

mysql_query("INSERT INTO `gallery_komm` (`id_foto`, `id_user`, `time`, `msg`) values('$foto[id]', '$user[id]', '$time', '".my_esc($msg)."')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
msg('Сообщение успешно добавлено');
}
}



err();
aut();

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_komm` WHERE `id_foto` = '$foto[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет комментариев\n";
echo "  </td>\n";
echo "   </tr>\n";

}

$q=mysql_query("SELECT * FROM `gallery_komm` WHERE `id_foto` = '$foto[id]' ORDER BY `id` ASC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
$ank2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$post[id_user]' LIMIT 1"));
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
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a>".online($ank2['id'])." (".vremja($post['time']).")\n";
echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
echo output_text($post['msg'])."<br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";


if ($k_page>1)str('?',$k_page,$page); // Вывод страниц


if (isset($user))
{

echo "<form method='post' name='message' action='?$passgen'>\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\"></textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input value=\"Отправить\" type=\"submit\" />\n";
echo "</form>\n";

}

echo "<div class=\"foot\">\n";
echo "&laquo;<a href='/foto/$ank[id]/$gallery[id]/$foto[id]/'>К фотографии</a><br />\n";
echo "&laquo;<a href='/foto/$ank[id]/$gallery[id]/'>К фотографиям</a><br />\n";
echo "&laquo;<a href='/foto/$ank[id]/'>К фотоальбомам</a><br />\n";

echo "</div>\n";
include_once '../sys/inc/tfoot.php';
exit;
?>