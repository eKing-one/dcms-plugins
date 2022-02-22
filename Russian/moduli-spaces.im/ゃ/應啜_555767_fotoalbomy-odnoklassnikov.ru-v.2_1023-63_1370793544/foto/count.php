<?
echo mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto`"),0);
echo "/";
echo mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery` WHERE `my` = '0'"),0);

?>