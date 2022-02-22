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


$set['title']='Кому посмотрел видео';
include_once '../sys/inc/thead.php';
title();
aut();

$id=intval($_GET['id']);


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_views` WHERE `id_videos` = '".$id."'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `videos_views` WHERE `id_videos` = '".$id."' ORDER BY `id_user` DESC LIMIT $start, $set[p_str]");
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Да это никто и не увидет :)\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($post = mysql_fetch_array($q))
{
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



echo "  <td class='p_m'>\n";
echo "<a href='/info.php?id=".$ank['id']."'>".$ank['nick']."</a>".online($ank['id'])."\n";
echo "  </td>\n";
echo "   </tr>\n";

}

echo "</table>\n";


if ($k_page>1)str("?id=".$id."&amp;",$k_page,$page); 

echo "<div class='reklf'>\n";
echo "- <a href='video.php?id=$id'> Вернутся назад</a><br />\n";
echo "</div>\n";


/////// by Kyber 2011 

include_once '../sys/inc/tfoot.php';
?>