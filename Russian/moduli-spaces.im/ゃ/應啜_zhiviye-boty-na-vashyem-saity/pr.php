<?
include_once '../sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
$bot_query=mysql_query("SELECT * from `bot_cron` WHERE `time_start`<='". date("%H") ."' and `time_end`>='". date("%H") ."'");
while($bot=mysql_fetch_array($bot_query)){
$enable=rand(1, 3);
if($enable==1){$plus=$time-rand(5, 10); // Чтобы разнообразие было
mysql_query("UPDATE `user` SET `date_last` = '$plus' WHERE `id` = '".$bot['user']."' LIMIT 1");} // if($enable==1)
echo 'Done<br/>';
                                           }