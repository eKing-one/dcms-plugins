<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Мой Ребёнок';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `id` = '".$it."' LIMIT 1"));
}else{
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
}
if (!$b)
{
echo "<div class='err'>";
if (isset($_GET['id']))
{
echo "Ребёнок не найден!";
}else{
echo "У вас нет ребёнка!";
}
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
$b2=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
$ank_1=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$b['papa']."' LIMIT 1"));
$ank_2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$b['mama']."' LIMIT 1"));
$za=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby_sp` WHERE `id_baby` = '".$b['id']."' AND `id_user` = '".$user['id']."' LIMIT 1"));
include_once 'inc/my_baby.php';
include_once 'inc/avatar.php';
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14'><center>";
echo "".ava_baby($b['id'])."";
echo "</center></td>";
echo "<td class='main'>";
echo "<b>Имя ребёнка:</b> ".$b['name']."<br />";
echo "<b>Пол ребёнка:</b> <img src='img/m_".$b['pol'].".png' width='16' alt='Simptom'><br />";
echo "<b>Папа:</b> ";
if ($ank_1['id']==0)
{
echo "Нету! ";
if (!$b2 && !$za && $user['pol']==1)
{
echo "(<a href='?id=".$b['id']."&amp;rod'>Стать папой</a>)";
}
echo "<br />";
}else{
echo "".online($ank_1['id'])." <a href='/info.php?id=".$ank_1['id']."'>".$ank_1['nick']."</a><br />";
}
echo "<b>Мама:</b> ";
if ($ank_2['id']==0)
{
echo "Нету! ";
if (!$b2 && !$za && $user['pol']==0)
{
echo "(<a href='?id=".$b['id']."&amp;rod'>Стать мамой</a>)";
}
echo "<br />";
}else{
echo "".online($ank_2['id'])." <a href='/info.php?id=".$ank_2['id']."'>".$ank_2['nick']."</a><br />";
}
echo "<b>Возраст:</b> ".bab_time($b['id'])."<br />";
echo "</td>";
echo "</tr></table>";
if ($user['id']==$ank_1['id'] || $user['id']==$ank_2['id'])
{
include_once 'inc/uv.php';
}
echo "<div class='main2'>";
echo "<img src='img/health.png' alt='Simptom'> <b>Здоровье:</b> <img src='img.php?rat=".intval($b['health'])."' alt='Simptom' />";
echo "</div>";
echo "<div class='main2'>";
echo "<img src='img/eda.png' alt='Simptom'> <b>Сытость:</b> <img src='img.php?rat=".intval($b['eda'])."' alt='Simptom' />";
echo "</div>";
echo "<div class='main2'>";
echo "<img src='img/happy.png' alt='Simptom'> <b>Энергия:</b> <img src='img.php?rat=".intval($b['happy'])."' alt='Simptom' />";
echo "</div>";
echo "<div class='main2'>";
echo "<img src='img/beauty.png' alt='Simptom'> <b>IQ:</b> ".$b['iq']."";
echo "</div>";
if ($user['id']==$ank_1['id'] || $user['id']==$ank_2['id'])
{
if ($b['progulka']==0 && $b['play']==0 && $b['skazka']==0)
{
echo "<a href='travel.php'><div class='main2'>";
echo "<img src='img/travel.png' alt='Simptom'> Пойти на прогулку";
echo "</div></a>";
echo "<a href='play_toys.php'><div class='main2'>";
echo "<img src='img/toys.png' alt='Simptom'> Играть с игрушками";
echo "</div></a>";
echo "<a href='eda.php'><div class='main2'>";
echo "<img src='img/eda.png' alt='Simptom'> Покормить";
echo "</div></a>";
echo "<a href='vrach.php'><div class='main2'>";
echo "<img src='img/life.png' alt='Simptom'> Отвести к доктору";
echo "</div></a>";
echo "<a href='skazka.php'><div class='main2'>";
echo "<img src='img/book.png' alt='Simptom'> Прочитать книгу";
echo "</div></a>";
}else{
if ($b['progulka']!=0)
{
echo "<div class='main2'>";
echo "<img src='img/travel.png' alt='Simptom'> Ваш ребёнок будет на прогулке до ".vremja($b['progulka'])."!";
echo "</div>";
}
else if ($b['play']!=0)
{
echo "<div class='main2'>";
echo "<img src='img/toys.png' alt='Simptom'> Ваш ребёнок будет играть с игрушкам до ".vremja($b['play'])."!";
echo "</div>";
}
else if ($b['skazka']!=0)
{
echo "<div class='main2'>";
echo "<img src='img/book.png' alt='Simptom'> Ваш ребёнок будет слушать чтение книги до ".vremja($b['skazka'])."!";
echo "</div>";
}
}
echo "<a href='set.php'><div class='main2'>";
echo "<img src='img/set.png' alt='Simptom'> Настройки";
echo "</div></a>";
}
if ($user['id']!=$ank_1['id'] && $user['id']!=$ank_2['id'] && $user['level']>=4)
{
echo "<a href='sets.php?id=".$b['id']."'><div class='main2'>";
echo "<img src='img/adm.png' alt='Simptom'> Настройки";
echo "</div></a>";
}
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>