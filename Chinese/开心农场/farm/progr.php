<?php
$rat=(int)$_GET['p'];
$limit=(int)$_GET['limit'];

$rats=$rat;
if($rat>=100){$rats=99;}
if($rat>100){$rat=100;}
if($limit==""){$limit=50;}
if($limit>100){$limit=100;}

    header("Content-type: image/gif");
    $im = imageCreateFromGIF("grafic.gif");

 //-----------------------------------------------------------//

     $color = imagecolorallocate($im, 234, 237, 237);
     $color2 = imagecolorallocate($im, 227, 222, 222);
     $color3 = imagecolorallocate($im, 204, 200, 200);
     $color4 = imagecolorallocate($im, 185, 181, 181);
     $color5 = imagecolorallocate($im, 197, 195, 195);


    imagefilledrectangle ($im, 2, 1, 99, 2, $color);
    imagefilledrectangle ($im, 1, 3, 100, 4, $color2);
    imagefilledrectangle ($im, 1, 5, 100, 6, $color3);
    imagefilledrectangle ($im, 1, 7, 100, 8, $color4);
    imagefilledrectangle ($im, 2, 9, 99, 10, $color5);

 //-----------------------------------------------------------//

     $color = imagecolorallocate($im, 255, 204, 204);
     $color2 = imagecolorallocate($im, 255, 153, 153);
     $color3 = imagecolorallocate($im, 255, 102, 102);
     $color4 = imagecolorallocate($im, 255, 51, 51);
     $color5 = imagecolorallocate($im, 255, 102, 102);
     $color6 = imagecolorallocate($im, 0, 0, 0);

    if($rat>0){
    imagefilledrectangle ($im, 2, 1, $rats, 2, $color);
    imagefilledrectangle ($im, 1, 3, $rat, 4, $color2);
    imagefilledrectangle ($im, 1, 5, $rat, 6, $color3);
    imagefilledrectangle ($im, 1, 7, $rat, 8, $color4);
    imagefilledrectangle ($im, 2, 9, $rats, 10, $color5);
    }


    ImageString($im, 1, 78, 2, "$rat%", $color6);

    ImageGIF($im);


?>