<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/loads.php';
include_once '../sys/inc/user.php';



$set['title']='Загрузки - Поиск файлов'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();

$search=NULL;
if (isset($_SESSION['search']))$search=$_SESSION['search'];
if (isset($_POST['search']))$search=$_POST['search'];
$_SESSION['search']=$search;

$search=ereg_replace("( ){2,}"," ",$search);
$search=ereg_replace("^( ){1,}|( ){1,}$","",$search);



if (isset($_GET['go']) && $search!=NULL)
{
$search_a=explode(' ', $search);

for($i=0;$i<count($search_a);$i++)
{
$search_a2[$i]='<span class="search_c">'.stripcslashes(htmlspecialchars($search_a[$i])).'</span>';
$search_a[$i]=stripcslashes(htmlspecialchars($search_a[$i]));
}

$q_search=str_replace('%','',$search);
$q_search=str_replace(' ','%',$q_search);
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `videos` WHERE `name` like '%".mysql_escape_string($q_search)."%'  "),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "<div class=\"post\">\nВидео не найдено</div>\n";
$q=mysql_query("SELECT * FROM `videos` WHERE `name` like '%".mysql_escape_string($q_search)."%'  ORDER BY `name` ASC LIMIT $start, $set[p_str] ");
$i=0;

echo "<table class='post'>\n";

while ($res = mysql_fetch_assoc($q))
{


echo '<div class="rekl">';
echo '<img src="http://i.ytimg.com/vi/'.$res['kod'].'/1.jpg" width="70" alt="screen" /><br />';

echo '<a href="video.php?id='.$res['id'].'"> '.$res['name'].'</a><br/>';
 
   
   
   
   
   
   
$like = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_like` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo "<img src ='img/like.png'>   <b>".$like." </b> ";
$pokz = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_views` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo '<img src ="img/kto.png"><b> '.$pokz.'</b>';
$comm = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_komm` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo "<img src='img/komm.png' alt=''/> <b> ".$comm."</b> ";

     echo '</div>';




}
echo "</table>\n";
if ($k_page>1)str("search.php?go&amp;",$k_page,$page); // Вывод страниц
}
else
echo "Введите название:";


echo "<form method=\"post\" action=\"search.php?go\" class=\"search\">\n";
$search=stripcslashes(htmlspecialchars($search));
echo "<input type=\"text\" name=\"search\" maxlength=\"64\" value=\"$search\" /><br />\n";
echo "<input type=\"submit\" value=\"Искать\" />\n";
echo "</form>\n";
echo '<table class="post"><tr><td class="icon14"><img src="img/back.png" alt=""/></td><td class="p_t"><a href="/videos/index.php">К разделам</a></table>';

include_once '../sys/inc/tfoot.php';
?>