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
$set['title']='快乐农场：：技能：：育种';
include_once '../../sys/inc/thead.php';
title();
aut();

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

if (isset($_GET['selection_up']))
{
if ($fuser['selection']==0 && $fuser['gems']>=8)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'8' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你成功地完成了第一期育种技能培训课程。花了 8 颗钻石');
}
if ($fuser['selection']==0 && $fuser['gems']<8)
{
$cntt=8-$fuser['gems'];
add_farm_event('你不够。 '.$cntt.' 一级技能 [B] 选择 [/B]');
}

if ($fuser['selection']==1 && $fuser['gems']>=12)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'12' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '2' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы успешно прошли второй курс обучения умения Селекция. Потрачено 12 алмазов');
}
if ($fuser['selection']==1 && $fuser['gems']<12)
{
$cntt=12-$fuser['gems'];
add_farm_event('У Вас не хватает '.$cntt.' алмазов для изучения умения [b]Селекция[/b] второго уровня');
}

if ($fuser['selection']==2 && $fuser['gems']>=15)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'15' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '3' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы успешно прошли третий курс обучения умения Селекция. Потрачено 15 алмазов');
}
if ($fuser['selection']==2 && $fuser['gems']<15)
{
$cntt=15-$fuser['gems'];
add_farm_event('У Вас не хватает '.$cntt.' алмазов для изучения умения [b]Селекция[/b] третьего уровня');
}

if ($fuser['selection']==3 && $fuser['gems']>=20)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'20' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '4' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы успешно прошли четвёртый курс обучения умения Селекция. Потрачено 20 алмазов');
}
if ($fuser['selection']==3 && $fuser['gems']<20)
{
$cntt=20-$fuser['gems'];
add_farm_event('У Вас не хватает '.$cntt.' алмазов для изучения умения [b]Селекции[/b] до четвёртого уровня');
}

if ($fuser['selection']==4 && $fuser['gems']>=30)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'30' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '5' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы успешно прошли пятый курс обучения умения Селекция. Потрачено 30 алмазов');
}
if ($fuser['selection']==4 && $fuser['gems']<30)
{
$cntt=30-$fuser['gems'];
add_farm_event('У Вас не хватает '.$cntt.' алмазов для изучения умения [b]Селекция[/b] до пятого уровня');
}
}

include_once H.'/farm/inc/str.php';
farm_event();

echo "<div class='rowup'>Умения :: Селекция</div>";
echo "<div class='rowdown'>";
echo "<table class='post'><tr><td><img src='/farm/img/plus.gif' alt='' /></td><td>";
echo "Увеличивает урожайность при сборе</td></tr></table>";

if ($fuser['selection']==0)
{
echo "<img src='/img/deletemail.gif' alt='' class='rpg' /> Данное умение у Вас ещё не изучено";
}

if ($fuser['selection']==1)
{
$nameur="первый";
$cost="12";
}

if ($fuser['selection']==2)
{
$nameur="второй";
$cost="15";
}

if ($fuser['selection']==3)
{
$nameur="третий";
$cost="20";
}

if ($fuser['selection']==4)
{
$nameur="четвёртый";
$cost="30";
}

if ($fuser['selection']==5)
{
$nameur="пятый (максимальный)";
}

if ($fuser['selection']!=0)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> Ваш уровень владения данным умением - <span class='underline'>".$nameur."</span>";
}

echo "<br />";
echo "&raquo; 1 уровень - +5% к урожаю (<img src='/farm/img/gems.png' alt='' class='rpg' />8)<br />&raquo; 2 уровень - +10% к урожаю (<img src='/farm/img/gems.png' alt='' class='rpg' />12)<br />&raquo; 3 уровень - +15% к урожаю (<img src='/farm/img/gems.png' alt='' class='rpg' />15)<br />&raquo; 4 уровень - +20% к урожаю (<img src='/farm/img/gems.png' alt='' class='rpg' />20)<br />&raquo; 5 уровень - +25% к урожаю (<img src='/farm/img/gems.png' alt='' class='rpg' />30)<br />";

if ($fuser['selection']==0)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> <span class='underline'><a href='?selection_up'>Изучить умение за <img src='/farm/img/gems.png' alt='' class='rpg' />8</a></span><br />";
}

if ($fuser['selection']>0 && $fuser['selection']<5)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> <span class='underline'><a href='?selection_up'>Повысить уровень умения за <img src='/farm/img/gems.png' alt='' class='rpg' />".$cost."</a></span><br />";
}

if ($fuser['selection']==5)
{
echo "<img src='/img/accept.png' alt='' class='rpg' /> <span class='underline'>Вы прошли весь курс обучения данному умению</span><br />";
}

echo '</div>';

echo "<div class='rowdown'>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/abilities/'>К списку умений</a><br />";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>Мои грядки</a>";
echo "</div>";

include_once '../../sys/inc/tfoot.php';
?>