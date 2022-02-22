<?php
$gang = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs` WHERE `id` = '$ank[gang]' LIMIT 1"));
if ($ank['gang']!=0)echo "<img src='/gangs/icons/admin.png' alt='*'> <a href='/gangs/gang.php?id=$ank[gang]'>В банде: ".htmlspecialchars($gang['name'])."</a><br/>";
?>