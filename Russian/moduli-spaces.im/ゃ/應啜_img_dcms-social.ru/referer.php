<?php
if((empty($_SERVER['HTTP_REFERER']))||(strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])!==false)){$reff=0;} else {$reff=1;};
if($reff){
$fd=fopen("referer.txt","ab");
if($fd){
flock($fd,LOCK_EX);
fwrite($fd,"".$_SERVER['HTTP_REFERER']."|\n");
fclose($fd);
};
}
?>

