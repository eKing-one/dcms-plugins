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

include_once '../sys/inc/thead.php';
$set['title']='Онлайн видео';
title();
$d = $_GET['d'];
aut();
$id=intval($_GET['id']);

////////  Создаем кейсы
switch ($d) {
   
case 'del':  

if ($user['level'] > 4) {
echo '<div class="err">Вы уверены?!<br /><a href="video.php?id='.$id.'&d=del_ok">Да</a> | <a href="video.php?id='.$id.'">Нет</a></div>';}
else {echo 'Вы не имеете право удалить это';}

break;
/////////////////////////
case 'del_ok':  

if ($user['level'] > 4) {
mysql_query("DELETE FROM `videos` WHERE `id` = '" .$id. "'");
echo '<div class="foot">Видео успешно удалено. <br /><a href="index.php">К разделам</a></div>'; }
break;
////////////////////////
case 'ren':  
$q=mysql_query("SELECT * FROM `videos` WHERE `id` = '".$id."'");
while ($res = mysql_fetch_assoc($q)) {
if ($user['level'] > 4) {
echo '<div class="p_t"><form action="video.php?id='.$res['id'].'&d=ren_ok" method="POST">
Введите название:<br />
<input type="text" name="name" value="'.$res['name'].'"><br />
<input type="submit" value="Изменить">
</form></div>';
} 
}
break;



case 'like':  
$pokaz = mysql_result(mysql_query("SELECT * FROM `videos_like` WHERE `id_user` = '$user[id]' AND `id_videos` = '".$id."' "),0);
if ($pokaz == 0){
mysql_query("INSERT INTO `videos_like` (`id_videos`, `id_user`) values ('".$id."', '$user[id]')");
}
if ($pokaz != 0){
mysql_query("DELETE FROM `videos_like` WHERE `id_user` = '$user[id]' AND `id_videos` = '".$id."'");
}
 {header("Location: video.php?id=".$id."".SID);exit;}


break;
/////////////////////////
case 'ren_ok':  

if ($user['level'] > 4) {
$name=mysql_real_escape_string($_POST['name']);

mysql_query("UPDATE `videos` SET `name` = '" . $name . "' WHERE `id`= '".$id."'");

echo '<div class="foot">Видео успешно переименовано. <br /><a href="video.php?id='.$id.'">К видео</a></div>'; }
break;
default:
/// Вывод разделов
$q=mysql_query("SELECT * FROM `videos` WHERE `id` = '".$id."' ");
while ($res = mysql_fetch_assoc($q)) {

echo '<table class="post"><tr><td class="icon14"><img src="img/video.png"></td><td class="p_t"> <b>'.$res['name'].'</b>';
if ($user['level'] > 4) {echo '<a href="video.php?id='.$res['id'].'&d=del"> <img src="img/del.png"></a><a href="video.php?id='.$res['id'].'&d=ren"> <img src="img/settings.png"></a>';     
}
echo '</table>';

echo' <div class="rekl">Смотреть онлайн:</div>';
echo'<iframe style="width:300px; height:200px;" src="http://www.youtube.com/embed/'.$res['kod'].'" frameborder="0"></iframe>';
echo' <div class="rekl">Скачать:</div>';

echo' <div class="rekl"><a href="http://islamfon.com/video/save/'.$res['kod'].'/flv-640x360">Скачать <b>flv</b></a> (640x360)<br />
<a href="http://islamfon.com/video/save/'.$res['kod'].'/mp4-640x360">Скачать <b>mp4</b></a> (640x360)<br />
<a href="http://islamfon.com/video/save/'.$res['kod'].'/flv-320x240">Скачать <b>flv</b></a> (320x240)<br />
<a href="http://islamfon.com/video/save/'.$res['kod'].'/3gp-320x240">Скачать <b>3gp</b></a> (320x240)<br />
<a href="http://islamfon.com/video/save/'.$res['kod'].'/3gp-176x144">Скачать <b>3gp</b></a> (176x144)</div><hr>';

$like = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_like` WHERE `id_videos` = '".$id."'  LIMIT 1"),0);
echo "<div class='rekl'> <img src ='img/like.png'><a href='video.php?id=".$id."&d=like'>Мне нравится </a> <a href='like.php?id=".$id."'>  <b>".$like."</b></a></div> ";
$pokz = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_views` WHERE `id_videos` = '".$id."'  LIMIT 1"),0);
echo '<div class="rekl"> <img src ="img/kto.png"> Просмотров: <a href="kto.php?id='.$id.'"><b>'.$pokz.'</b></a></div>';
$pokaz = mysql_result(mysql_query("SELECT * FROM `videos_views` WHERE `id_user` = '$user[id]' AND `id_videos` = '".$id."' "),0);
if ($pokaz == 0){
mysql_query("INSERT INTO `videos_views` (`id_videos`, `id_user`) values ('".$id."', '$user[id]')");
}

echo'<div class="rekl"><img src="img/time.png" alt=""/> Добавлено: <b>'.vremja($res['time']).'</b></div>';
$ank=get_user($res['id_user']);
echo'<div class="rekl"><img src="img/user.png" alt=""/> Добавил: <a href="/info.php?id='.$ank['id'].'"><b>'.$ank['nick'].'</b></a></div>';
$comm = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_komm` WHERE `id_videos` = '".$id."'  LIMIT 1"),0);
echo "<div class='rekl'><img src='img/komm.png' alt=''/> <a href='komm.php?id=".$id."'>Комментарии</a> <b>".$comm."</b></div> ";





echo' <div class="rekl">Скриншоты:</div>';
echo' <img src="http://i.ytimg.com/vi/'.$res['kod'].'/1.jpg" width="80" alt="screen" /> | <img src="http://i.ytimg.com/vi/'.$res['kod'].'/2.jpg" width="80" alt="screen" /> | <img src="http://i.ytimg.com/vi/'.$res['kod'].'/3.jpg" width="80" alt="screen" />';






}
}

err();
echo '<table class="post"><tr><td class="icon14"><img src="img/back.png" alt=""/></td><td class="p_t"><a href="/videos/index.php">К разделам</a></table>';

include_once '../sys/inc/tfoot.php';
?>