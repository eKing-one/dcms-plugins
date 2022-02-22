<?
//NcolorV3stable by leynix
function extract16color($color16){
$mask16=array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
$color_mask=array("r","g","b");
$true_color=array("r"=>0,"g"=>0,"b"=>0);
$color16=substr($color16,1);
for($icm=0; $icm<count($color_mask); $icm++){
$color16_temp=array(substr($color16,($icm*2),1),substr($color16,($icm*2)+1,1));
for ($ic=0; $ic<2; $ic++){
for($i=0; $i<count($mask16); $i++){
if ($color16_temp[$ic]==$mask16[$i]){
$color16_temp[$ic]=$i;
break;
}
}
}
$true_color[$color_mask[$icm]]=((int)$color16_temp[0]*16)+(int)$color16_temp[1];
}
return $true_color;
}
function make16color($color10){
$mask16=array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
$color_mask=array("r","g","b");
$true_color="#";
for($icm=0; $icm<count($color_mask); $icm++){
$color10_temp=(int)$color10[$color_mask[$icm]];
$true_color.=$mask16[(int)($color10_temp/16)];
$true_color.=$mask16[$color10_temp%16];
}
return $true_color;
}
function GradientText($text,$sColor,$eColor){
$color_mask=array("r","g","b");
$color_move=array("r"=>0,"g"=>0,"b"=>0);
$color_add=array("r"=>0,"g"=>0,"b"=>0);
$word=array();
$length=strlen($text);
$output="";
$Gzip="";
$current_color="";
$max=0;
if($length>0){
$sColor=extract16color($sColor);
$eColor=extract16color($eColor);
$cColor=$sColor;
for($i=0; $i<count($color_mask); $i++){
$temp=$sColor[$color_mask[$i]]-$eColor[$color_mask[$i]];
$color_add[$color_mask[$i]]=abs($temp);
if(abs($temp)>$max){
$max=abs($temp);
}
if($temp<0){
$color_move[$color_mask[$i]]=1;
} else if ($temp>0){
$color_move[$color_mask[$i]]=-1;
} else {
$color_move[$color_mask[$i]]=0;
}
}
for($i=0; $i<$length; $i++){
$char=substr($text,$i,1);
$test=ord($char);
if($test>=128 and $test<=255 | $test=""){
$char=substr($text,$i,2);
$i++;
}
$word[]=$char;
}
}
$length=count($word);
$koeff_add=@(($max/$length)/$max)*100;
for($i=0; $i<$length; $i++){
for($i2=0; $i2<count($color_mask); $i2++){
$add=(($color_add[$color_mask[$i2]]/100)*$koeff_add)*$color_move[$color_mask[$i2]];
$cColor[$color_mask[$i2]]+=$add;
}
$current_color=make16color($cColor);
if(!$i){
$output.='<font color="'.$current_color.'">'.$word[$i];
$Gzip=$current_color;
} else {
if($current_color==$Gzip | $word[$i]==" "){
$output.=$word[$i];
} else {
$output.='</font><font color="'.$current_color.'">'.$word[$i];
$Gzip=$current_color;
}
}
}
if(strlen($output)){
$output.="</font>";
}
return $output;
}

//
$set['meta_keywords']=(isset($set['meta_keywords']))?$set['meta_keywords']:null;
$set['meta_description']=(isset($set['meta_description']))?$set['meta_description']:null;


if ($set['meta_keywords']!=NULL)
{
function meta_keywords($str)
{
global $set;
return str_replace('</head>', '<meta name="keywords" content="'.$set['meta_keywords'].'" />'."\n</head>", $str);
}
ob_start('meta_keywords');
}


if ($set['meta_description']!=NULL)
{
function meta_description($str)
{
global $set;
return str_replace('</head>', '<meta name="description" content="'.$set['meta_description'].'" />'."\n</head>", $str);
}
ob_start('meta_description');
}



if (file_exists(H."style/themes/$set[set_them]/head.php"))
include_once H."style/themes/$set[set_them]/head.php";
else
{
$set['web']=false;
//header("Content-type: application/vnd.wap.xhtml+xml");
//header("Content-type: application/xhtml+xml");
header("Content-type: text/html");
echo '<?xml version="1.0" encoding="utf-8"?>';
?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<title><?echo $set['title'];?></title>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" href="/style/themes/<?echo $set['set_them'];?>/style.css" type="text/css" />
<link rel="alternate" title="Новости RSS" href="/news/rss.php" type="application/rss+xml" />
</head>
<body>
<div class="body">
<?
}

?>