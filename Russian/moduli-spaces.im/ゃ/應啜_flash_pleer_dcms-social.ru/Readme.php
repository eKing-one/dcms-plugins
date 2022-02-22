Флеш плеер музыки!
Установка
Распаковать в корень!
Вставить в нужном месте ниженаписанный код! 
К примеру в файле info.php
И наслаждаемся!
P.S. Я не проверял! Но работать должно! XREX

if ($user['music'] == '') 
{
} 
else 
{
echo '<object type="application/x-shockwave-flash" data="mp3player.swf" width="200" height="20" id="mp3player" name="mp3player">';
echo '<param name="movie" value="mp3player.swf" />';
echo '<param name="flashvars" value="mp3='.$user['music'].'" />';
echo '</object>';
}
if (isset($user) && $user['id']==$ank['id'])
{
echo "</br><a href='/music2.php'><font color='blue'>Сменить песню!</font></a>";
}