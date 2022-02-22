<?

if (file_exists(H."style/themes/$set[set_them]/foot.php"))
{
include_once H."style/themes/$set[set_them]/foot.php";
}
else
{


list($msec, $sec) = explode(chr(32), microtime());
echo "<div class='foot'>";
echo "<a href='/'>На главную</a><br />\n";




echo "<a href='/users.php'>Регистраций: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `user`"), 0)."</a><br />\n";
echo "<a href='/online.php'>Сейчас на сайте: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > ".(time()-600).""), 0)."</a><br />\n";
echo "<a href='/online_g.php'>Гостей на сайте: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `guests` WHERE `date_last` > ".(time()-600)." AND `pereh` > '0'"), 0)."</a><br />\n";
if (isset($user) && $user['level']!=0) echo "Генерация: ".round(($sec + $msec) - $conf['headtime'], 3)." сек<br />\n";
echo "</div>\n";
echo "<div class='rekl'>\n";
rekl(3);
echo "</div>\n";
echo '<object class="playerpreview" id="myFlash" type="application/x-shockwave-flash" data="pmp3.swf" width=1 height=1>
<param name="movie" value="pmp3.swf" />
<param name="AllowScriptAccess" value="always" />
<param name="FlashVars" value="listener=myListener&amp;interval=500" />
</object>';
echo '<script type="text/javascript">
function play()
{
document.getElementById("myFlash").SetVariable("method:setUrl", "/message.mp3");
document.getElementById("myFlash").SetVariable("method:play", "");
document.getElementById("myFlash").SetVariable("method:enabled", "true");
}
</script>';
echo "</div>\n</body>\n</html>";
}
echo '<center><a href="/zh.php?url=http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">На странице ошибка</a></center>';
ob_free;
exit;
?>