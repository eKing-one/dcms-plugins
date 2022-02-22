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
Error_Reporting(0);
only_reg();
$set['title']='Выгрузка фона в анкету';
include_once '../sys/inc/thead.php';
title();
if (isset($_GET['act']) && $_GET['act']=='delete' && isset($_GET['ok']))
{
@unlink(H."fon/img_anketa/$user[id].jpg");
@unlink(H."fon/img_anketa/$user[id].gif");
@unlink(H."fon/img_anketa/$user[id].png");
msg('Фон был успешно удален!');
echo '<img src="/fon/ico/go.gif" alt=""/> ';
echo '<a href="/info.php">Перейти к себе</a><br />';
echo '<img src="/fon/ico/back.gif" alt=""/> ';
echo "<a href='/fon/'>Назад</a><br />\n";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_FILES['file']))
{
if (eregi('\.jpe?g$',$_FILES['file']['name']) && $imgc=@imagecreatefromjpeg($_FILES['file']['tmp_name']))
{
if (imagesx($imgc)>148 || imagesy($imgc)>148)
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=148; // ширина
$dstH=148; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=148;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=148;
$dstW=ceil($dstH/$prop);
}
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);
@chmod(H."fon/img_anketa/$user[id].jpg",0777);
@chmod(H."fon/img_anketa/$user[id].gif",0777);
@chmod(H."fon/img_anketa/$user[id].png",0777);
@unlink(H."fon/img_anketa/$user[id].jpg");
@unlink(H."fon/img_anketa/$user[id].gif");
@unlink(H."fon/img_anketa/$user[id].png");
imagejpeg($screen,H."fon/img_anketa/$user[id].jpg",100);
@chmod(H."fon/img_anketa/$user[id].jpg",0777);
imagedestroy($screen);
}
else
{
copy($_FILES['file']['tmp_name'], H."fon/img_anketa/$user[id].jpg");
}
msg("Фон успешно загружен!");
$price = $user['balls'] - 150;
mysql_query("UPDATE `user` SET `balls` = '$price' WHERE `id` = '".$user['id']."'");
}
elseif (eregi('\.gif$',$_FILES['file']['name']) && $imgc=@imagecreatefromgif($_FILES['file']['tmp_name']))
{
include_once 'sys/inc/gif_resize.php';
$screen=gif_resize(fread ( fopen ($_FILES['file']['tmp_name'], "rb" ), filesize ($_FILES['file']['tmp_name']) ),48,48);
@chmod(H."fon/img_anketa/$user[id].jpg",0777);
@chmod(H."fon/img_anketa/$user[id].gif",0777);
@chmod(H."fon/img_anketa/$user[id].png",0777);
@unlink(H."fon/img_anketa/$user[id].jpg");
@unlink(H."fon/img_anketa/$user[id].gif");
@unlink(H."fon/img_anketa/$user[id].png");
file_put_contents(H."fon/img_anketa/$user[id].gif", $screen);
@chmod(H."fon/img_anketa/$user[id].gif",0777);
msg("Фон успешно загружен!");
$price = $user['balls'] - 150;
mysql_query("UPDATE `user` SET `balls` = '$price' WHERE `id` = '".$user['id']."'");
}
elseif (eregi('\.png$',$_FILES['file']['name']) && $imgc=@imagecreatefrompng($_FILES['file']['tmp_name']))
{
if (imagesx($imgc)>148 || imagesy($imgc)>148)
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=148; // ширина
$dstH=148; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=148;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=148;
$dstW=ceil($dstH/$prop);
}
$screen=ImageCreate($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);
@chmod(H."fon/img_anketa/$user[id].jpg",0777);
@chmod(H."fon/img_anketa/$user[id].gif",0777);
@chmod(H."fon/img_anketa/$user[id].png",0777);
@unlink(H."fon/img_anketa/$user[id].jpg");
@unlink(H."fon/img_anketa/$user[id].gif");
@unlink(H."fon/img_anketa/$user[id].png");
imagepng($screen,H."fon/img_anketa/$user[id].png");
@chmod(H."fon/img_anketa/$user[id].png",0777);
imagedestroy($screen);
}
else
{
copy($_FILES['file']['tmp_name'], H."fon/img_anketa/$user[id].png");
}
msg("Фон успешно загружен!");
$price = $user['balls'] - 150;
mysql_query("UPDATE `user` SET `balls` = '$price' WHERE `id` = '".$user['id']."'");
}
else
{
$err='Неверный формат файла!';
}
}
err();
aut();
$fon_anketa = mysql_fetch_array(mysql_query("SELECT * FROM `fon_anketa` WHERE `user_id` = '".$user['id']."'"));
if ($fon_anketa['fon_id']>=1)
{
echo '<div class = "foot">';
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'Вы не можете выгрузить фон! Для начала продайте фон, который вы выбирали из списка!<br />';
echo '<img src="/fon/ico/prod.png" alt=""/> ';
echo '<a href="/fon/fon_anketa/index.php?act=ok&amp;select=0">Продать фон</a><br />';
echo '</div>';
}else{
if (is_file(H."fon/img_anketa/$user[id].gif") || is_file(H."fon/img_anketa/$user[id].jpg") || is_file(H."fon/img_anketa/$user[id].png"))
{
echo "<form method='post' enctype='multipart/form-data' action='?act=delete'>\n";
echo "<input value='Удалить фон' type='submit' title='Удалить фон'/>\n";
echo "</form>\n";
}
if (isset($_GET['act']) && $_GET['act']=='delete')
{
echo "<form class='foot1' action='?act=delete&amp;ok' method=\"post\">";
echo "<div class='err'>Вы уверены что хотите удалить фон?</div>\n";
echo "<span style='float : right;'>\n";
echo "<a href='?'></a><br />\n";
echo " </span>";
echo "<input class='submit' type='submit' value='Удалить' /><br />\n";
echo "</form>";
}else{
if ($user['fon_vugr_zapret2']==0)
{
if ($user['balls'] >= 150)
{
echo '<div class = "foot">';
echo '<img src="/fon/ico/by.gif" alt=""/> ';
echo 'У вас: '.$user['balls'].' балов.<br/>';
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo "Можно загружать картинки форматов: GIF, JPG, PNG<br />\n";
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo "За выгрузку фона у вас будет снято 150 балов!<br />\n";
echo '</div>';
echo "<form method='post' enctype='multipart/form-data' action='?$passgen'>\n";
echo "   <tr>\n";
echo "  <td colspan='2'>\n";
echo "<input type='file' name='file' accept='image/*,image/gif,image/png,image/jpeg' />\n";
echo "<br /><input value='Изменить фон' type='submit' />\n";
echo "  </td>\n";
echo "   </tr>\n";
echo "</form>\n";
}else{
echo '<div class = "foot">';
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'У вас нехватает балов для возможности выгружать фон!<br />';
echo '</div>';
}
}else{
echo '<div class = "aut">';
echo '<img src="/fon/ico/stop.png" alt=""/> ';
echo 'Вам запрещено выгружать фон!<br />';
echo '</div>';
}
}
}
echo "<div class='foot'>\n";
if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
{
echo '<img src="/fon/ico/back.gif" alt=""/> ';
echo "<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n";
}
echo '<img src="/fon/ico/back.gif" alt=""/> ';
echo "<a href='/umenu.php'>Мое меню</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>