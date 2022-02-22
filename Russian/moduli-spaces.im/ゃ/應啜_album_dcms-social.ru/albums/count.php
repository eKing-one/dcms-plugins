<?
$k_p=mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto`",$db), 0);
$k_n= mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto` WHERE `time` > '".(time()-60*60*$set['loads_new_file_hour'])."'",$db), 0);
if ($k_n==0)$k_n=NULL;
else $k_n='/+'.$k_n;
echo "$k_p$k_n";
?>