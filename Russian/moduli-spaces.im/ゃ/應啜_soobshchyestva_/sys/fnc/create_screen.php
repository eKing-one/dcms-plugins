<?
function create_screen($file, $path, $w=128, $h=128, $dop, $need_name=NULL)
{
if (is_file($file))
{
$movie=@new ffmpeg_movie($file);
if($imgc=@imagecreatefromstring(file_get_contents($file)))
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=$w; // ширина
$dstH=$h; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=$w;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=$h;
$dstW=ceil($dstH/$prop);
}
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);
if ($need_name==NULL)$name = $w."-".$h."_".$dop."screen.png";
else $name = $need_name;
imagepng($screen, $path.$name);
imagedestroy($screen);
}
elseif($movie)
{
$k_frames=$movie->getFrameCount(); //к-тво кадров
for($kp=1;$kp<=60;$kp++) //выбираем случайный кадр
{
$image=@$movie->getFrame($kp);
if($image)$show_img=$image->toGDImage(); //если кадр в нормальном состо€нии, то выбираем его дл€ дальнейшей работы
}
if(@$show_img)
{
$imgc=$show_img;
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=$w; // ширина
$dstH=$h; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=$w;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=$h;
$dstW=ceil($dstH/$prop);
}
$screen=imagecreatetruecolor($dstW, $dstH); // создаем изображение
$black = imagecolorallocate ($screen, 0, 0, 0); // и предаем ему черного фона
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y); // налаживаем на него кадр
imagedestroy($imgc);
$name = rand(12345,67890)."screen.png";
imagepng($screen, $path.$name);
imagedestroy($screen);
/*
###########################################################################
#	—истему переделал Killer (Special For New DiaryMod)												 #
#	јс€ 75319624																		 #
#	 ошелек R408800828608																 #
#	(с) Killer																			 #
#	¬се права защищены. ћод нельз€ продавать/отдавать и т.п. третим лицам (хоть даже ето лицо ваш друг, брат или сват...)	 #
###########################################################################
*/
}
}
else $name = NULL;
}
else $name = NULL;
if (!isset($name))$name = NULL;
return $name;
}
?>