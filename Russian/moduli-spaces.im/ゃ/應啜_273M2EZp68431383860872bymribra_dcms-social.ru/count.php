<?
$sum = mysql_result(mysql_query("SELECT COUNT(*) FROM `group`"),0);
echo $sum;
?>