<?
if (!isset($user) && !isset($_GET['id_user'])){header("Location: /foto/?".SID);exit;}
if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id_user']))$ank['id']=intval($_GET['id_user']);
$ank=get_user($ank['id']);
if (!$ank){header("Location: /foto/?".SID);exit;}

//---------------------------создаем альбом с личными фото-----------------------------------------//
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery` WHERE `id_user` = '$ank[id]' AND `name` = 'Личные фото'"),0)==0)
{
mysql_query("INSERT INTO `gallery` (`id_user`, `name`, `my`) values('$ank[id]', 'Личные фото', '1')");
}
//-------------------------------alex-borisi-------------------------------------------------------//

$set['title']=$ank['nick'].' - Фотоальбомы'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();

include 'inc/gallery_act.php';
err();
aut();



$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery` WHERE `id_user` = '$ank[id]' AND `my` = '0'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];


include 'inc/gallery_form.php';

$my_alb=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery` WHERE `id_user` = '$ank[id]' AND `my` = '1'  LIMIT 1"));
if (isset($user) && $user['id']==$ank['id'] || user_access('foto_alb_del')){

if (isset($user) && $user['id']==$ank['id'])
echo "<img src='/style/icons/pht2.png' alt=''/> <a href='/foto/$ank[id]/$my_alb[id]/?act=upload'>Добавить личное фото</a><br />\n";

}
echo "<table class='post'>\n";
echo "   <tr>\n";
echo "  <td class='p_m'>\n";
echo "<a href='/foto/$ank[id]/$my_alb[id]/'>$my_alb[name]</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_gallery` = '$my_alb[id]'"),0)." фото)";
echo "  </td>\n";
echo "   </tr>\n";
$zz = mysql_query("SELECT * FROM `gallery_foto` WHERE `id_gallery` = '$my_alb[id]' ORDER BY `id` DESC LIMIT 5");

$fotos = mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_gallery` = '$my_alb[id]' ORDER BY id DESC"));
if ($fotos==null){
echo "   <tr>\n";
echo "  <td class='p_m'>\n";
echo "<img src='/foto/foto48/0.png' alt='Нет фото' />";
echo "  </td>\n";
echo "   </tr>\n";
}
else
{
echo "   <tr>\n";
echo "  <td class='p_m'>\n";
while ($xx = mysql_fetch_assoc($zz))
{

echo "<a href='/foto/$ank[id]/$my_alb[id]/$xx[id]/'><img style='padding: 2px;' src='/foto/foto50/$xx[id].$xx[ras]' alt='Фото_$xx[id]'/></a>";

}
echo "  </td>\n";
echo "   </tr>\n";
}
echo "</table>\n";
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет фотоальбомов\n";
echo "  </td>\n";
echo "   </tr>\n";

}

$q=mysql_query("SELECT * FROM `gallery` WHERE `id_user` = '$ank[id]' AND `my` = '0' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{

echo "   <tr>\n";

echo "  <td class='p_m'>\n";
echo "<a href='/foto/$ank[id]/$post[id]/'>$post[name]</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_gallery` = '$post[id]'"),0)." фото)\n";


echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
echo "  <td class='p_m'>\n";
$z = mysql_query("SELECT * FROM `gallery_foto` WHERE `id_gallery` = '$post[id]' ORDER BY `id` LIMIT 5");

$foto = mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_gallery` = '$post[id]' ORDER BY RAND()"));
if ($foto==null){
echo "<img src='/foto/foto48/0.png' alt='Нет фото' />";
}
else
{
while ($x = mysql_fetch_assoc($z))
{
echo "<a href='/foto/$ank[id]/$post[id]/$x[id]/'><img style='padding: 2px;' src='/foto/foto50/$x[id].$x[ras]' alt='Фото_$x[id]'/></a>";
}
}
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";




if ($k_page>1)str('?',$k_page,$page); // Вывод страниц


include_once '../sys/inc/tfoot.php';
exit;
?>