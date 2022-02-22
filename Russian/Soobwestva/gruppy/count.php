<?php
echo mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy`"), 0);
echo '/';
echo mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users`"), 0);
?>