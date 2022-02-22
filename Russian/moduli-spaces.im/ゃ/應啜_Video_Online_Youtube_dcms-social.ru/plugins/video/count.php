<?
$k_p = mysql_result(mysql_query("SELECT COUNT(*) FROM `video`"),0);
$k_n = mysql_result(mysql_query("SELECT COUNT(*) FROM `video` WHERE `time` > '$ftime'"),0);

if ($k_n > 0) $k_n = '<font color="red">+' . $k_n . '</font>'; else $k_n = null;
echo '(' . $k_p . ') ' . $k_n;
?>
