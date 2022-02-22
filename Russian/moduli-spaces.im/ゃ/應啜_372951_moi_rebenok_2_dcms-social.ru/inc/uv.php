<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if ($b['health']>=0 && $b['health']<=40)
{
echo "<div class='err'>";
echo "У ващего ребёнка плохое здоровье!";
echo "</div>";
}
if ($b['eda']>=0 && $b['eda']<=40)
{
echo "<div class='err'>";
echo "Ваш ребёнок испытывает голод!";
echo "</div>";
}
if ($b['happy']>=0 && $b['happy']<=40)
{
echo "<div class='err'>";
echo "Ваш ребёнок испытывает усталость!";
echo "</div>";
}
?>