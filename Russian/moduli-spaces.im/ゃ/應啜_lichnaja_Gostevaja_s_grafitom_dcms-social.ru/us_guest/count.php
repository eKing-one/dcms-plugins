<?
$k_p = mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_comms` WHERE `id_user_adm` = '$ank[id]'".($user['group_access'] < 7?" AND `hide` = '0'":null)), 0);
$k_n = mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_comms` WHERE `id_user_adm` = '$ank[id]'".($user['group_access'] < 7?" AND `hide` = '0' AND `time` > '".(time()-86400)."'":null)), 0);
if (!$k_n)$k_n=NULL;
else $k_n = "/<span style='color: red;'>+$k_n</span>";
echo $k_p.$k_n;
?>