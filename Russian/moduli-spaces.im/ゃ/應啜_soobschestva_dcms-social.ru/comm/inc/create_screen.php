<?
function create_screen($file, $path, $w=128, $h=128, $dop, $need_name=NULL)
{
if (is_file($file))
{
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
else $name = NULL;
}
else $name = NULL;
if (!isset($name))$name = NULL;
return $name;
}
?>