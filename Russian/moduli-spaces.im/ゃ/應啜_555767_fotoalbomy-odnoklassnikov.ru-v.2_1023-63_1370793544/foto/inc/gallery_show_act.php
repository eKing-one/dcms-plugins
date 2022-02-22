<?

if ((user_access('foto_alb_del') || isset($user) && $user['id']==$ank['id']) && isset($_GET['act']) && $_GET['act']=='delete' && isset($_GET['ok']))
{
$q=mysql_query("SELECT * FROM `gallery_foto` WHERE `id_gallery` = '$gallery[id]'");
while ($post = mysql_fetch_assoc($q))
{
@unlink(H."sys/gallery/50/$post[id].jpg");
@unlink(H."sys/gallery/48/$post[id].jpg");
@unlink(H."sys/gallery/128/$post[id].jpg");
@unlink(H."sys/gallery/640/$post[id].jpg");
@unlink(H."sys/gallery/foto/$post[id].jpg");

mysql_query("DELETE FROM `gallery_foto` WHERE `id` = '$post[id]' LIMIT 1");
mysql_query("DELETE FROM `gallery_frend` WHERE `id_foto` = '$post[id]'");

}
if ($user['id']!=$ank['id'])admin_log('Фотогалерея','Фотоальбомы',"Удаление альбома $gallery[name] (фотографий: ".mysql_num_rows($q).")");
mysql_query("DELETE FROM `gallery` WHERE `id` = '$gallery[id]' LIMIT 1");
msg('Фотоальбом успешно удален');
aut();



echo "<div class=\"foot\">\n";

echo "&laquo;<a href='/foto/$ank[id]/'>К фотоальбомам</a><br />\n";

echo "</div>\n";
include_once '../sys/inc/tfoot.php';
exit;
}





if (isset($user) && $user['id']==$ank['id'] && isset($_FILES['file']))
{
if ($imgc=@imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name'])))
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])),1);
if (isset($_POST['translit1']) && $_POST['translit1']==1)$name=translit($name);
if ($name==null)$name=esc(stripcslashes(htmlspecialchars(preg_replace('#\.[^\.]*$#i', NULL, $_FILES['file']['name'])))); // имя файла без расширения)),1);



if (!preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui",$name))$err='В названии фото присутствуют запрещенные символы';
if (strlen2($name)<3)$err='Короткое название';
if (strlen2($name)>32)$err='Название не должно быть длиннее 32-х символов';
$name=my_esc($name);



$msg=$_POST['opis'];
if (isset($_POST['translit2']) && $_POST['translit2']==1)$msg=translit($msg);
//if (strlen2($msg)<10)$err='Короткое описание';
if (strlen2($msg)>1024)$err='Длина описания превышает предел в 1024 символов';
$msg=my_esc($msg);
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);


if ($img_x>$set['max_upload_foto_x'] || $img_y>$set['max_upload_foto_y'])$err='Размер изображения превышает ограничения в '.$set['max_upload_foto_x'].'*'.$set['max_upload_foto_y'];

if (!isset($err)){
mysql_query("INSERT INTO `gallery_foto` (`id_gallery`, `name`, `ras`, `type`, `opis`, `id_user`) values ('$gallery[id]', '$name', 'jpg', 'image/jpeg', '$msg', '$user[id]')");
$id_foto=mysql_insert_id();
mysql_query("UPDATE `gallery` SET `time` = '$time' WHERE `id` = '$gallery[id]' LIMIT 1");




$q = mysql_query("SELECT * FROM `frends` WHERE `user` = '$user[id]' AND `i` = '1'");
$fot_id=$id_foto;


$msg_lenta=" $user[nick], загрузил новое фото загрузил новое [url=/foto/$ank[id]/$gallery[id]/$fot_id/]фото[/url]";

while ($f = mysql_fetch_array($q))
{
$a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$f[frend]' LIMIT 1"));



mysql_query("INSERT INTO `lenta` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$a[id]', '".mysql_real_escape_string($msg_lenta)."', '$time')"); 
}



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
//imagedestroy($imgc);
imagejpeg($screen,H."sys/gallery/48/$id_foto.jpg",90);
@chmod(H."sys/gallery/48/$id_foto.jpg",0777);
imagedestroy($screen);

if ($img_x==$img_y)
{
$dstW=128; // ширина
$dstH=128; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=128;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=128;
$dstW=ceil($dstH/$prop);
}

$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
//imagedestroy($imgc);
$screen=img_copyright($screen); // наложение копирайта
imagejpeg($screen,H."sys/gallery/128/$id_foto.jpg",90);
@chmod(H."sys/gallery/128/$id_foto.jpg",0777);
imagedestroy($screen);

if ($img_x>640 || $img_y>640){
if ($img_x==$img_y)
{
$dstW=640; // ширина
$dstH=640; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=640;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=640;
$dstW=ceil($dstH/$prop);
}

$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
//imagedestroy($imgc);
$screen=img_copyright($screen); // наложение копирайта
imagejpeg($screen,H."sys/gallery/640/$id_foto.jpg",90);
imagedestroy($screen);
$imgc=img_copyright($imgc); // наложение копирайта
imagejpeg($imgc,H."sys/gallery/foto/$id_foto.jpg",90);
@chmod(H."sys/gallery/foto/$id_foto.jpg",0777);
}
else
{
$imgc=img_copyright($imgc); // наложение копирайта

imagejpeg($imgc,H."sys/gallery/640/$id_foto.jpg",90);
imagejpeg($imgc,H."sys/gallery/foto/$id_foto.jpg",90);
@chmod(H."sys/gallery/foto/$id_foto.jpg",0777);
}

@chmod(H."sys/gallery/640/$id_foto.jpg",0777);



imagedestroy($imgc);


crop(H."sys/gallery/640/$id_foto.jpg", H."sys/gallery/avatar/$id_foto.tmp.jpg");
resize(H."sys/gallery/avatar/$id_foto.tmp.jpg", H."sys/gallery/avatar/$id_foto.jpg", 150, 150);

@chmod(H."sys/gallery/avatar/$id_foto.jpg",0777);
@unlink(H."sys/gallery/avatar/$id_foto.tmp.jpg");

resize(H."sys/gallery/avatar/$id_foto.jpg", H."sys/gallery/50/$id_foto.jpg", 50, 50);

@chmod(H."sys/gallery/50/$id_foto.jpg",0777);

$_SESSION['message'] = 'Фотография успешно добавлена';
header("Location: /foto/$ank[id]/$gallery[id]/$id_foto/");
}
}
else $err='Выбранный Вами формат изображения не поддерживается';
}



?>