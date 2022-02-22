</h3><?
echo "<div class='menu'>\n";
if (isset($_GET['pass']) && $_GET['pass']='ok')
msg('Пароль отправлен вам на E-mail');
//aut();


if ((!isset($_SESSION['refer']) || $_SESSION['refer']==NULL)
&& isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=NULL &&
!ereg('mail\.php',$_SERVER['HTTP_REFERER']))
$_SESSION['refer']=str_replace('&','&amp;',ereg_replace('^http://[^/]*/','/', $_SERVER['HTTP_REFERER']));

if (isset($user))
{

include_once H.'sys/inc/umenu.php';

echo "<br />\n";




echo "<span class=\"ank_n\">Баллы:</span> <span class=\"ank_d\">$user[balls]</span><br />\n";
echo "<span class=\"ank_n\">Рейтинг:</span> <span class=\"ank_d\">$user[rating]</span><br />\n";
if ($user['level']>0)
{
if ($user['ip']!=0)echo "<span class=\"ank_n\">IP:</span> <span class=\"ank_d\">".long2ip($user['ip'])."</span><br />\n";
if ($user['ua']!=NULL)echo "<span class=\"ank_n\">UA:</span> <span class=\"ank_d\">$user[ua]</span><br />\n";
if (opsos($user['ip']))echo "<span class=\"ank_n\">Пров:</span> <span class=\"ank_d\">".opsos($user['ip'])."</span><br />\n";
}

echo "<br />\n";
echo "<a href='/exit.php'>Выход</a><br />\n";
}
else
{
?>
<div id="lb_overlay" style="display: none;">
</div>
<div id="lb_box" style="display: none;">
<div id="lb_close" onclick="onCloseLoginBox();" title="Закрыть">
</div>
<div id="lb_box_content">
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<tbody>
<tr valign="top">
<td style="padding: 10px 0px 15px 15px;" width="100%">
<h2>Вход на сайт</h2>
<form style="margin: 0px; padding: 0px;" method="post" action="/"><br>
<input type='text' name='nick' class="a_field" maxlength='32' style="width:130px" value="Логин">
<br>
<input type='password' name='pass' class="a_field" maxlength='32' style="width:130px" value="Password" />
<br>
<td align="centr" style="padding-top:39px; padding-right:10px;">
<input name="image" type="image" src="/style/themes/<?echo $set['set_them'];?>/images/login.jpg" style="width:57px; height:40px; border: 1px solid #E27301;" />
<br>
</form>
</td><strong>DCMS.SU </strong>
</tbody>
</table>
</div>
</div>
<a href="Open" onclick="onOpenLoginBox(); return false;">
<strong>Вход</strong>
</a>
<br>
<a href='/pass.php'>Забыли пароль?</a>
<br>Ещё не зарегистрированы? <?echo "<a href='/reg.php'><b>Регистрация</b></a><br />\n";}?>
<div class="moduletable">
<br>
<h3>Информация</h3>
<br>
<?
list($msec, $sec) = explode(chr(32), microtime());

echo "<a href='/'>На главную</a><br />\n";
echo "<a href='/users.php'>Регистраций: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `user`"), 0)."</a><br />\n";

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > ".(time()-600).""), 0)>0)
{
echo "<marquee>\n";

echo "<a href='/online.php'>Сейчас на сайте</a>: ";
if (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > ".(time()-600).""), 0)==1)
{
echo "<b>только вы</b>.";
}
else
{
$q = mysql_query("SELECT * FROM `user` WHERE `date_last` > '".(time()-600)."' ORDER BY `date_last` DESC");
while ($ank = mysql_fetch_array($q))
{
$u_on[]="<b>$ank[nick]</b>";
}
echo implode(', ',$u_on).'.';
}


echo "</marquee>\n";
}

echo "<a href='/online_g.php'>Гостей на сайте: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `guests` WHERE `date_last` > ".(time()-600)." AND `pereh` > '0'"), 0)."</a><br />\n";

if (isset($user) && $user['level']!=0) echo "Генерация: ".round(($sec + $msec) - $conf['headtime'], 3)." сек<br />\n";


?></div>
<div class="moduletable">
<br>
<h3>Баннеры</h3>
<br>
<?
echo "<div class='rekl'>\n";
rekl(3);
echo "</div>\n";
?>
</div>
</div>
</div>
</div>
<div id="right_bottom">
</div>