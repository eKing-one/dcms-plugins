<?
$k_p=mysql_result(mysql_query("SELECT COUNT(*) FROM `lenta_site`",$db), 0);
$k_n= mysql_result(mysql_query("SELECT COUNT(*) FROM `lenta_site` WHERE `time` > '".$ftime."'",$db), 0);
if ($k_n==0)$k_n=NULL;
else $k_n='+'.$k_n;
echo "($k_p) <font color='red'>$k_n</font>";
?>
