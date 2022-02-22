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
$set['title']='Мои видео';
include_once '../sys/inc/thead.php';
title();
aut(); // форма авторизации


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `videos`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

$q=mysql_query("SELECT * FROM `videos` WHERE `id_user` = '$user[id]' ORDER BY `time` DESC  LIMIT $start, $set[p_str]");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Видео нет<br />";
echo "  </td>\n";
echo "   </tr>\n";
}
echo "<table class='post'>\n";
while ($res= mysql_fetch_array($q)){

echo '<div class="rekl">';
echo '<img src="http://i.ytimg.com/vi/'.$res['kod'].'/1.jpg" width="70" alt="screen" /><br />';

echo '<a href="video.php?id='.$res['id'].'"> '.$res['name'].'</a><br/>';
 
   
   
   
   
   
   
$like = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_like` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo "<img src ='img/like.png'> <b>".$like." </b> ";
$pokz = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_views` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo '<img src ="img/kto.png"><b> '.$pokz.'</b>';
$comm = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_komm` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo "<img src='img/komm.png' alt=''/> <b> ".$comm."</b> ";

     echo '</div>';




}
echo "</table>\n";

if ($k_page>1)str("?",$k_page,$page); // Вывод страниц
echo '<table class="post"><tr><td class="icon14"><img src="img/back.png" alt=""/></td><td class="p_t"><a href="/videos/index.php">К разделам</a></table>';

include_once '../sys/inc/tfoot.php';
?>