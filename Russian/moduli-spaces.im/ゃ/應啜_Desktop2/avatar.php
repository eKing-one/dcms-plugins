<?
	// Dcms-Fiera
	// http://dcms-help.ru
	// ShaMan
	
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

only_reg();
$set['title']='Мой аватар';
include_once 'sys/inc/thead.php';
title();

if (isset($_FILES['file']))
{


if (preg_match('#\.jpe?g$#i',$_FILES['file']['name']) && $imgc=@imagecreatefromjpeg($_FILES['file']['tmp_name']))
{
if (imagesx($imgc)>48 || imagesy($imgc)>48)
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=48; // ширина
$dstH=48; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=48;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=48;
$dstW=ceil($dstH/$prop);
}

$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);
@chmod(H."sys/avatar/$user[id].jpg",0777);
@chmod(H."sys/avatar/$user[id].gif",0777);
@chmod(H."sys/avatar/$user[id].png",0777);
@unlink(H."sys/avatar/$user[id].jpg");
@unlink(H."sys/avatar/$user[id].gif");
@unlink(H."sys/avatar/$user[id].png");
imagejpeg($screen,H."sys/avatar/$user[id].jpg",100);
@chmod(H."sys/avatar/$user[id].jpg",0777);
imagedestroy($screen);
}
else
{
copy($_FILES['file']['tmp_name'], H."sys/avatar/$user[id].jpg");
}

msg("Аватар успешно установлен");
}
elseif (preg_match('#\.gif$#i',$_FILES['file']['name']) && $imgc=@imagecreatefromgif($_FILES['file']['tmp_name']))
{
include_once 'sys/inc/gif_resize.php';
$screen=gif_resize(fread ( fopen ($_FILES['file']['tmp_name'], "rb" ), filesize ($_FILES['file']['tmp_name']) ),48,48);
@chmod(H."sys/avatar/$user[id].jpg",0777);
@chmod(H."sys/avatar/$user[id].gif",0777);
@chmod(H."sys/avatar/$user[id].png",0777);
@unlink(H."sys/avatar/$user[id].jpg");
@unlink(H."sys/avatar/$user[id].gif");
@unlink(H."sys/avatar/$user[id].png");

file_put_contents(H."sys/avatar/$user[id].gif", $screen);
@chmod(H."sys/avatar/$user[id].gif",0777);

msg("Аватар успешно установлен");
}
elseif (preg_match('#\.png$#i',$_FILES['file']['name']) && $imgc=@imagecreatefrompng($_FILES['file']['tmp_name']))
{
if (imagesx($imgc)>48 || imagesy($imgc)>48)
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=48; // ширина
$dstH=48; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=48;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=48;
$dstW=ceil($dstH/$prop);
}

$screen=ImageCreate($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);


@chmod(H."sys/avatar/$user[id].jpg",0777);
@chmod(H."sys/avatar/$user[id].gif",0777);
@chmod(H."sys/avatar/$user[id].png",0777);
@unlink(H."sys/avatar/$user[id].jpg");
@unlink(H."sys/avatar/$user[id].gif");
@unlink(H."sys/avatar/$user[id].png");
imagepng($screen,H."sys/avatar/$user[id].png");
@chmod(H."sys/avatar/$user[id].png",0777);
imagedestroy($screen);
}
else
{

copy($_FILES['file']['tmp_name'], H."sys/avatar/$user[id].png");
}

msg("Аватар успешно установлен");
}
else
{
$err='Неверный формат файла';
}
}

err();
aut();
	//Вывод страницы (ShaMan)------------------------------
	echo "<form method='post' enctype='multipart/form-data' action='?$passgen'>
		<table class='post'>
		<tr>
	<td class='icon' rowspan='2'>\n";
	avatar($user['id']);
		echo "</td>
			<td class='p_t'>
			Ваш текущий аватар
			</td>
			</tr>
			<tr>
			<td class='p_m'>
			Можно загружать картинки форматов: GIF, JPG, PNG<br />
			Качественное преобразование GIF-анимации не гарантируется<br />
			</td>
			</tr>
			<tr>
			<td colspan='2'>
			<input type='file' name='file' accept='image/*,image/gif,image/png,image/jpeg' />
			<br /><input value='Заменить' type='submit' />
			</td>
			</tr>
			</table>
			</form>
			<div class='foot'>\n";
if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n";
echo "&laquo;<a href='umenu.php'>Мое меню</a><br /></div>\n";

include_once 'sys/inc/tfoot.php';
?>