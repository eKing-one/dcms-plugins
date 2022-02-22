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
only_reg();
include_once '../sys/inc/thead.php';
$set['title']='Онлайн видео';
title();
$d = $_GET['d'];
aut();
////////  Создаем кейсы
$id=intval($_GET['id']);

switch ($d) {
    case 'video':
{

echo '<div class="p_t"><form action="razdel.php?id='.$id.'&d=add" method="POST">
Введите ссылку или код видео:<br />
<input type="text" name="kod" value=""><br />
<input type="submit" value="Добавить видео">
</form></div>';

}
break;
//////////////////////////     
case 'add':  

$kod=$_POST['kod'];
if (strlen2($kod) >= 11)
	{
		$kod = preg_replace('#(.*)(v=)#isU', '', $kod); 
		$kod = preg_replace('/\&.*/', '', $kod); 


$str = file_get_contents('http://m.youtube.com/watch?v=' . $kod);

		$name = preg_replace('#(<)(.*)(<title>)#isU', '', $str); 

		$name = preg_replace('#(</title>)(.*)(</html>)#isU', '', $name); 

		$name = preg_replace('#( - YouTube)#isU', '', $name); 

		$name = my_esc($name);
		
mysql_query("INSERT INTO `videos` (`name`,`kod`,`time`,`id_cat`,`id_user`) values('$name', '$kod', '$time', '".$id."', '$user[id]')");
echo '<div class="foot">Видео успешно добавлено. <br /><a href="razdel.php?id='.$id.'">К разделу</a></div>'; }
break;
default:
/// Создание раздела
$q=mysql_query("SELECT * FROM `videos_cat` WHERE `id`='".$id."' ");
while ($res = mysql_fetch_assoc($q)) {
echo '<div class="title">'.$res['name'].'</div>';
}
if (isset($user)) {echo '<table class="post"><tr><td class="icon14"><img src="img/video_add.png" alt=""/></td><td class="p_t"><a href="razdel.php?id='.$_GET['id'].'&amp;d=video">Добавить видео</a></table>';}
/// Вывод разделов
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos`  WHERE `id_cat`='".$id."' "), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
echo '<div class="err">Нет видео</div>';
}

$q=mysql_query("SELECT * FROM `videos` WHERE `id_cat`='".$id."' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($res = mysql_fetch_assoc($q)) {

echo '<div class="rekl">';
echo '<img src="http://i.ytimg.com/vi/'.$res['kod'].'/1.jpg" width="70" alt="screen" /><br />';

echo '<a href="video.php?id='.$res['id'].'"> '.$res['name'].'</a><br/>';
 
   
   
   
   
   
   
$like = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_like` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo "  <img src ='img/like.png'><b>".$like." </b> ";
$pokz = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_views` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo '<img src ="img/kto.png"><b> '.$pokz.'</b>';
$comm = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_komm` WHERE `id_videos` = '".$res['id']."'  LIMIT 1"),0);
echo "<img src='img/komm.png' alt=''/> <b> ".$comm."</b> ";

     echo '</div>';
   
   
   
   
   }
}


if ($k_page>1)str('?id='.$id.'&amp;',$k_page,$page); // Вывод страниц
echo '<table class="post"><tr><td class="icon14"><img src="img/back.png" alt=""/></td><td class="p_t"><a href="/videos/index.php">К разделам</a></table>';

err();
include_once '../sys/inc/tfoot.php';
?>