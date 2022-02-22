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
$set['title']='Оценки';
include_once '../sys/inc/thead.php';
title();

if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id']))$ank['id']=intval($_GET['id']);


if ($ank['id']!=$user['id'])
{
$ank=get_user($ank['id']);
$set['title']=$ank['nick'].''; // заголовок страницы
include_once '../sys/inc/thead.php';
echo "<span class=\"status\">Вы не можете просматривать чужие оценки</span><br />\n";

if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "<div class='foot2'>&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n</div>\n";

include_once '../sys/inc/tfoot.php';
exit;
}

$ank=get_user($ank['id']);
if(!$ank){header("Location: /index.php?".SID);exit;}

mysql_query("UPDATE `gallery_rating` SET `ready` = '0' WHERE `avtor` = '$ank[id]'");
err();

aut(); // форма авторизации




$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_rating` WHERE `avtor` = '$ank[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q=mysql_query("SELECT * FROM `gallery_rating` WHERE `avtor` = '$ank[id]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет оценок\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($post = mysql_fetch_assoc($q))
{
//$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
$ank2=get_user($post['id_user']);
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = $post[id_foto]"));
echo "   <tr>\n";

echo "  <td>\n";

status($ank2['id']);
echo " <a href='/info.php?id=$ank2[id]'>$ank2[nick]</a>".online($ank2['id'])."<br />\n";
echo "".vremja($post['time'])."";
echo "  </td>\n";
echo "  <td>\n";
echo "<div style='display:inline;'><a href='/foto/$user[id]/$foto[id_gallery]/$foto[id]/'><img  src='/foto/foto50/$foto[id].$foto[ras]' alt='*' ></a></div>";
$rat=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_rating` WHERE `id_foto` = $foto[id] AND `like` = '6'"), 0);
if ($rat>0)echo "<div style='display:inline;margin-left:-20px;vertical-align:top;'><img style='padding-top:-15px;'   src='/style/icons/6.png'/></div>";

echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";


if ($k_page>1)str("komm.php?id=".$ank['id'].'&amp;',$k_page,$page); // Вывод страниц

include_once '../sys/inc/tfoot.php';
?>