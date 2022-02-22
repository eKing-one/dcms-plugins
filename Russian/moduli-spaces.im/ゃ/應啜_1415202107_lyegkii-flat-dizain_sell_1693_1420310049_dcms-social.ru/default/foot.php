<?
list($msec, $sec) = explode(chr(32), microtime());

if ($_SERVER['PHP_SELF'] != '/index.php')
{
?>
<div class="main2">
<img src="/style/icons/icon_glavnaya.gif" alt="*" /> <a href="/index.php">На главную</a>
</div>
<?
}
?>

<div class="foot"><table style="width:100%" cellspacing="0" cellpadding="0"><tr><td style="vertical-align:top;width:20%;white-space: nowrap;">
На сайте:<font color="#fff">
<a href="/online.php"><?=mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > ".(time()-600).""), 0)?></a> &
<a href="/online_g.php"><?=mysql_result(mysql_query("SELECT COUNT(*) FROM `guests` WHERE `date_last` > ".(time()-600)." AND `pereh` > '0'"), 0)?></a>
</td></font>
<td style="vertical-align:top;width:1%;white-space: nowrap;"><?
if (!$set['web'])
echo '<a href="/?t=web"><font color="#fff">Версия для компьютера</font></a>';
?></td>
</tr></table>
</div>


</div>
</body>
</html>
<?
exit;
?>







