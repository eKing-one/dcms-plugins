<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';
only_reg();
$set['title']='快乐农场：：技能：：电栅栏';
include_once '../../sys/inc/thead.php';
title();
aut();

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

if (isset($_POST['gems']) && is_numeric($_POST['gems']) && $_POST['gems']>0)
{
$gemsp=intval($_POST['gems']);
$z_time=86400*$gemsp;
$ztime=time()+$z_time;
if ($fuser['gems']<$gemsp)
{
add_farm_event('你没有足够的钻石来完成这项任务');
}
else
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'$gemsp' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `zabor_time` = '".$ztime."' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你成功地延长了栅栏的作用时间 '.$gemsp.' 钻石');
}
}

include_once '../inc/str.php';
farm_event();


echo "<div class='rowup'>技能：电栅栏</div>";
echo "<div class='rowdown'>";
echo "<table class='post'><tr><td><img src='/farm/img/electro.png' alt='' /></td><td><b>Электрический забор</b> защищает огород по периметру и не позволяет допустить проникновения воров на Ваши грядки. А если кто и сунется - его ждёт сильный удар током</td></tr></table>";
echo "<img src='/img/accept.png' alt='' class='rpg' /> Стоимость аренды - <img src='/farm/img/gems.png' alt='' class='rpg' />1 на 1 сутки<br />";
echo "<img src='/img/accept.png' alt='' class='rpg' /> На Вашем счету: <img src='/farm/img/gems.png' alt='' class='rpg' />".sklon_after_number("$fuser[gems]","алмаз","алмаза","алмазов",1)."<br />";
if ($fuser['zabor_time']<time())
{
echo "<form action='?".$passgen."' method='post'>";
echo "&raquo; Введите количество алмазов:<br />";
echo "<input type='text' name='gems' value='10' /><br /><input type='submit' value='Обменять' /></form>";
}
else
{
$vremja=$fuser['zabor_time']-time();

$timediff=$vremja;
$oneMinute=60; 
$oneHour=60*60; 
$oneDay=60*60*24; 
$dayfield=floor($timediff/$oneDay); 
$hourfield=floor(($timediff-$dayfield*$oneDay)/$oneHour); 
$minutefield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute); 
$secondfield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute)); 
if($dayfield>0)$day=$dayfield.'д. ';
$time_1=$day.$hourfield."ч. ".$minutefield."м.";

echo "<img src='/farm/img/electro.png' alt='' class='rpg' /> Электрозабор ещё активен <img src='/farm/img/time.png' alt='' class='rpg' />".$time_1."";
}
echo "</div>";

echo "<div class='rowdown'>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/abilities/'>到技能列表</a><br />";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的床</a>";
echo "</div>";
include_once '../../sys/inc/tfoot.php';
?>