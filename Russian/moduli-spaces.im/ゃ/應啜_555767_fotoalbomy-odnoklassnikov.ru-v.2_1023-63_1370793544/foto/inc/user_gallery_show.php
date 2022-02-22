<?
if (!isset($user) && !isset($_GET['id_user'])){header("Location: /foto/?".SID);exit;}
if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id_user']))$ank['id']=intval($_GET['id_user']);
$ank=get_user($ank['id']);
if (!$ank){header("Location: /foto/?".SID);exit;}
$gallery['id']=intval($_GET['id_gallery']);

//---------------------------создаем альбом с личными фото-----------------------------------------//
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery` WHERE `id_user` = '$ank[id]' AND `name` = 'Личные фото'"),0)==0)
{
mysql_query("INSERT INTO `gallery` (`id_user`, `name`, `my`) values('$ank[id]', 'Личные фото', '1')");
}
//-------------------------------alex-borisi-------------------------------------------------------//

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery` WHERE `id` = '$gallery[id]' AND `id_user` = '$ank[id]' LIMIT 1"),0)==0){header("Location: /foto/$ank[id]/?".SID);exit;}
$gallery=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery` WHERE `id` = '$gallery[id]' AND `id_user` = '$ank[id]' LIMIT 1"));



$set['title']=$ank['nick'].' - '.$gallery['name'].' - Фотоальбом'; // заголовок страницы

include_once '../sys/inc/thead.php';

title();

include 'inc/gallery_show_act.php';
err();
aut();


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_gallery` = '$gallery[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

include 'inc/gallery_show_form.php';
echo "<div class='foot'><b>Фотографии $k_post</b></div>";
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет фотографий\n";
echo "  </td>\n";
echo "   </tr>\n";
}

$q=mysql_query("SELECT * FROM `gallery_foto` WHERE `id_gallery` = '$gallery[id]' ORDER BY `id` DESC LIMIT $start, $set[p_str]");

echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";

while ($post = mysql_fetch_assoc($q))
{

echo "<a href='/foto/$ank[id]/$gallery[id]/$post[id]/'><img style='padding: 2px;' src='/foto/foto50/$post[id].$post[ras]' alt='Фото_$post[id]'/></a>";


}
echo "   </td>\n";
echo "   </tr>\n";
echo "</table>\n";




if ($k_page>1)str('?',$k_page,$page); // Вывод страниц



echo "<div class=\"foot\">\n";
echo "&laquo;<a href='/foto/$ank[id]/'>К фотоальбомам</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
exit;
?>