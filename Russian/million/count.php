<?
if (isset($user))mysql_query("DELETE FROM `million_who` WHERE `id_user` = '$user[id]'");
mysql_query("DELETE FROM `million_who` WHERE `time` < '".($time-120)."'");
echo mysql_result(mysql_query("SELECT COUNT(*) FROM `million_who`"),0).' человек';
?>