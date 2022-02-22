<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$dd=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby_shop_igrushki` WHERE `id` = '".$it."' LIMIT 1"));
if (!$dd || $dd['id']==0)
{
echo "<div class='err'>";
echo "Игрушка не найдена!";
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
echo "У вас нет ".$dd['cena']." баллов для покупки игрушки ".$dd['name']."!";
echo "</div>";
}else{
$t=time()+3600;
mysql_query("UPDATE `baby` SET `play` = '".$t."' WHERE `id` = '".$b['id']."' LIMIT 1");
$y=$user['balls']-$dd['cena'];
mysql_query("UPDATE `user` SET `balls` = '".$y."' WHERE `id` = '".$user['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Вы успешно успешно купили своему ребёнку игрушку ".$dd['name']."! Теперь ваш ребёнок будет занят на целый час, можете отдохнуть)";
echo "</div>";
}
}else{
echo "<div class='err'>";
echo "Вы уверены что хотите купить своему ребёнку игрушку ".$dd['name']."?";
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
echo "<a href='play_toys.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
?>