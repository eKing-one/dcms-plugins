<?
list($msec, $sec) = explode(chr(32), microtime());





if ($_SERVER['PHP_SELF']!='/index.php') {
echo "<div class='foot'>";
echo "<img src='/style/icons/icon_glavnaya.gif' alt='' /> <a href='/'>На главную</a>\n";


echo "</div>\n";
}

echo '<div class="copy">';
echo '<center>© <a href="http://dcms-social.ru">Test.Ru</a> - 2013г</center>';
echo '</div>';







echo "<div class='foot'>\n";
echo "На сайте: <a href='/online.php'>".mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > ".(time()-600).""), 0)."</a> & \n";
echo "<a href='/online_g.php'>".mysql_result(mysql_query("SELECT COUNT(*) FROM `guests` WHERE `date_last` > ".(time()-600)." AND `pereh` > '0'"), 0)."</a>\n";
if (!$set['web'])
echo " | <a href='/?t=web'>Версия для компьютера</a>";
echo "</div>";

echo "<div class='rekl'>\n";
$page_size = ob_get_length(); ob_end_flush(); 
rekl(3);
echo '<center>PGen: '.round(($sec + $msec) - $conf['headtime'], 3).'сек	</center>'; 
echo "</div>";



?>

