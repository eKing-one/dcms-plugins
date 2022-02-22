<?php
include_once("ini.php");
$rand=zapros("select rand from randoms where usa='".addslashes($usa)."' and ip='".$ip."'");
$r=rand(1000,9999);
if(!$rand)
{
mysql_query("insert into randoms set rand=".$r.", usa='".addslashes($usa)."', ip='".$ip."'");
$rand=$r;
}
header("Content-type: image/png");
$string = $rand;
$im     = imagecreatefrompng("captcha.png");
$color = imagecolorallocate($im, 123, 56, 239);
$px     = (imagesx($im) - 7.5 * strlen($string)) / 2;
imagestring($im, 3, $px, -2, $string, $color);
imagepng($im);
imagedestroy($im);

?>

