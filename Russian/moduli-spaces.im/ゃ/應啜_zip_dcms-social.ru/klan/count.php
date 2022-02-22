<?
$a = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan`"),0);
$b = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `activaty` = '0'"),0);
echo "($a/$b)<br/>\n";
?>