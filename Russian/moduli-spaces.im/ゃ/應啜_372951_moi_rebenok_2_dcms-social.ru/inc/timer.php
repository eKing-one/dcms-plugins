<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if ($b['progulka']!=0)
{
echo "<div class='logo'><center>";
echo "<img src='img/logo_travel.png' alt='Simptom'>";
echo "</center></div>";
echo "<div class='err'>";
echo "Ваш ребёнок сейчас на прогулке!";
echo "</div>";
echo "<a href='my_baby.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if ($b['play']!=0)
{
echo "<div class='err'>";
echo "Ваш ребёнок сейчас играет с игрушками!";
echo "</div>";
echo "<a href='my_baby.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if ($b['skazka']!=0)
{
echo "<div class='err'>";
echo "Ваш ребёнок сейчас слушает чтение книги!";
echo "</div>";
echo "<a href='my_baby.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
?>