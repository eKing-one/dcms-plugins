<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$dd=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby_shop_eda` WHERE `id` = '".$it."' LIMIT 1"));
if (!$dd || $dd['id']==0)
{
echo "<div class='err'>";
echo "Еда не найдена!";
echo "</div>";
echo "<a href='play_toys.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['yes']))
{
if ($user['balls']<$dd['cena'])
{
echo "<div class='err'>";
echo "У вас нет ".$dd['cena']." баллов для покупки еды ".$dd['name']."!";
echo "</div>";
}else{
$y=$user['balls']-$dd['cena'];
mysql_query("UPDATE `user` SET `balls` = '".$y."' WHERE `id` = '".$user['id']."' LIMIT 1");
$h=$b['health']+$dd['health'];
if ($h>=100)
{
$t=100;
}else{
$t=$h;
}
mysql_query("UPDATE `baby` SET `health` = '".$t."' WHERE `id` = '".$b['id']."' LIMIT 1");
$e=$b['eda']+$dd['health'];
if ($e>=100)
{
$f=100;
}else{
$f=$e;
}
mysql_query("UPDATE `baby` SET `eda` = '".$f."' WHERE `id` = '".$b['id']."' LIMIT 1");
if ($b['happy']<=95)
{
$r=$b['happy']+5;
}else{
$r=100;
}
mysql_query("UPDATE `baby` SET `happy` = '".$r."' WHERE `id` = '".$b['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Вы успешно успешно покормили своего ребёнка! Здоровье и сытость вашего ребёнка пополнилось на ".$dd['health']."%";
echo "</div>";
}
}else{
echo "<div class='err'>";
echo "Вы уверены что хотите покормить своего ребёнка ".$dd['name']."?";
echo "</div>";
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='main2'><center>";
echo "<a href='?id=".$dd['id']."&amp;yes'><img src='img/yes.png' alt='Simptom'> Да</a>";
echo "</center></td>";
echo "<td class='main2'><center>";
echo "<a href='?'><img src='img/no.png' alt='Simptom'> Нет</a>";
echo "</center></td>";
echo "</tr></table>";
}
echo "<a href='eda.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
?>