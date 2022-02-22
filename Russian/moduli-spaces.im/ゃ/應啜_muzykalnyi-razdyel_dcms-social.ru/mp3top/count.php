<?
$k_p = mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_files` WHERE `ras` = 'mp3'"), 0);
$k_n= mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_files` WHERE `ras` = 'mp3' AND `time` > '".$ftime."'"), 0);
if ($k_n==0)$k_n=NULL;
else $k_n='+'.$k_n;
echo "($k_p) <font color='red'>$k_n</font>";
?>
