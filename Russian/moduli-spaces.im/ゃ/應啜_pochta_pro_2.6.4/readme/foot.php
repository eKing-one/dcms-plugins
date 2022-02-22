<?

list($msec, $sec) = explode(chr(32), microtime());
if ($_SERVER['PHP_SELF']=='/index.php')
{
}else{
echo "<div class='foot'>";
echo "<img src='/style/themes/$set[set_them]/icons/home.png' alt='[&laquo;]' />&nbsp;<a href='/'>На главную</a><br />\n";
echo "</div>\n";
}


echo "<div class='foot'>";


echo '<span id="online">';

echo "<a href='/users.php'>Пользователей: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `user`"), 0)."</a><br /> \n";

echo "<a href='/online.php'>Онлайн: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > ".(time()-600).""), 0)."</a> / \n";

echo "<a href='/online_g.php'>Гостей: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `guests` WHERE `date_last` > ".(time()-600)." AND `pereh` > '0'"), 0)."</a>\n";

echo "</span>";
echo '<object class="playerpreview" id="myFlash" type="application/x-shockwave-flash" data="pmp3.swf" width=1 height=1>
<param name="movie" value="pmp3.swf" />
<param name="AllowScriptAccess" value="always" />
<param name="FlashVars" value="listener=myListener&amp;interval=500" />
</object>';
echo "</div>\n";



echo "<div class='rekl'>\n";

rekl(3);

echo "</div>";

if (isset($user) && $user['id']==1) echo "<center>Генерация: (".round(($sec + $msec) - $conf['headtime'], 3)." сек)</center>\n";

////////////////это мод
echo "</body>\n</html>";
echo '<script type="text/javascript">
function play()
{
document.getElementById("myFlash").SetVariable("method:setUrl", "/message.mp3");
document.getElementById("myFlash").SetVariable("method:play", "");
document.getElementById("myFlash").SetVariable("method:enabled", "true");
}
</script>';
////////////////это мод


exit;

?>