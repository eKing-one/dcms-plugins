<?php
$level = 0;
$folder_level = '';
while (!file_exists($folder_level . 'favicon.ico') && $level < 5) {
$folder_level .= '../';
++$level;
} 
unset($level);
define('F', $folder_level);
if (isset($_GET['name'])) {$name = $_GET['name'];}else{$name = "";} 
if (isset($_GET['prev'])) {$prev = $_GET['prev'];}else{$prev = 60;} 
if (isset($_GET['dir'])) {$dir = $_GET['dir'];}else{$dir = 'group/pic/';} 
if (preg_match('|^[a-z0-9_\-/]+$|i', $dir) && preg_match('|^[a-z0-9_\.\-]+$|i', $name)) {
if (file_exists(F . $dir . '/' . $name)) {
$getim = getimagesize(F . $dir . '/' . $name);
if ($getim[2] == 1 || $getim[2] == 2 || $getim[2] == 3) {
            $width = $getim[0];
            $height = $getim[1];

            if ($width > $prev || $height > $prev) {
                $x_ratio = $prev / $width;
                $y_ratio = $prev / $height;

                if (($x_ratio * $height) < $prev) {
                    $tn_height = ceil($x_ratio * $height);
                    $tn_width = $prev;
                } else {
                    $tn_width = ceil($y_ratio * $width);
                    $tn_height = $prev;
                } 
                // -------------------------------//
                if ($getim[2] == 2) {
                    $img = imagecreatefromjpeg(F . $dir . '/' . $name);
                    $dst = imagecreatetruecolor($tn_width, $tn_height);
                    imagecopyresampled($dst, $img, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);

                    header('content-type: image/jpeg');
                    header('Content-Disposition: filename="' . $name . '"');
                    imagejpeg ($dst, null, 75);
                    imagedestroy($img);
                    imagedestroy($dst);
                } 
                // -------------------------------//
                if ($getim[2] == 1) {
                    $img = imagecreatefromgif(F . $dir . '/' . $name);
                    $dst = imagecreatetruecolor($tn_width, $tn_height);

                    $colorTransparent = imagecolortransparent($img);
                    imagepalettecopy($img, $dst);
                    imagefill($dst, 0, 0, $colorTransparent);
                    imagecolortransparent($dst, $colorTransparent);
                    imagetruecolortopalette($dst, true, 256);

                    imagecopyresampled($dst, $img, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);

                    header('content-type: image/gif');
                    header('Content-Disposition: filename="' . $name . '"');
                    imagegif ($dst);
                    imagedestroy($img);
                    imagedestroy($dst);
                } 
                // -------------------------------//
                if ($getim[2] == 3) {
                    $img = imagecreatefrompng(F . $dir . '/' . $name);
                    $dst = imagecreatetruecolor($tn_width, $tn_height);

                    $colorTransparent = imagecolortransparent($img);
                    imagepalettecopy($img, $dst);
                    imagefill($dst, 0, 0, $colorTransparent);
                    imagecolortransparent($dst, $colorTransparent);
                    imagetruecolortopalette($dst, true, 256);

                    imagecopyresampled($dst, $img, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);

                    header('content-type: image/png');
                    header('Content-Disposition: filename="' . $name . '"');
                    imagepng ($dst);
                    imagedestroy($img);
                    imagedestroy($dst);
                } 
            } else {
                $filename = file_get_contents(F . $dir . '/' . $name);
                header('Content-type: ' . $getim['mime']);
                header('Content-Disposition: filename="' . $name . '"');
                header('Content-Length: ' . strlen($filename));
                echo $filename;
            } 
        } 
    } 
} 

exit;

?>