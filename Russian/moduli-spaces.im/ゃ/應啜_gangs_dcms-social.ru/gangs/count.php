<?
$k_p='('.mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs`",$db), 0);
$k_u=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users`",$db), 0).')';
$k_n= mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `time` > '".(time()-86400)."'",$db), 0);
$k_un= mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `time` > '".(time()-86400)."'",$db), 0);
if ($k_n==0)$k_n=NULL;
else $k_n="/<font color='red'>+$k_n</font>";
if ($k_un==0)$k_un=NULL;
else $k_un=" <font color='red'>+$k_un</font>";

echo "$k_p/$k_u$k_un";
?>
