<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['dvor']))
{
echo "<div class='logo'><center>";
echo "<img src='img/logo_travel.png' alt='Simptom'>";
echo "</center></div>";
if (isset($_GET['yes']))
{
$t_1=time()+3600;
mysql_query("UPDATE `baby` SET `progulka` = '".$t_1."' WHERE `id` = '".$b['id']."'");
echo "<div class='msg'>";
echo "Вы успешно вывели своего ребёнка на прогулку во двор!";
echo "</div>";
}else{
echo "<div class='err'>";
echo "Вы уверены что хотите вывести своего ребёнка на прогулку во двор на 1 час?";
echo "</div>";
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='main2'><center>";
echo "<a href='?dvor&amp;yes'><img src='img/yes.png' alt='Simptom'> Да</a>";
echo "</center></td>";
echo "<td class='main2'><center>";
echo "<a href='?'><img src='img/no.png' alt='Simptom'> Нет</a>";
echo "</center></td>";
echo "</tr></table>";
}
echo "<a href='?'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if (isset($_GET['park']))
{
echo "<div class='logo'><center>";
echo "<img src='img/logo_travel.png' alt='Simptom'>";
echo "</center></div>";
if (isset($_GET['yes']))
{
$t_2=time()+7200;
mysql_query("UPDATE `baby` SET `progulka` = '".$t_2."' WHERE `id` = '".$b['id']."'");
echo "<div class='msg'>";
echo "Вы успешно вывели своего ребёнка на прогулку в парк!";
echo "</div>";
}else{
echo "<div class='err'>";
echo "Вы уверены что хотите вывести своего ребёнка на прогулку в парк на 2 часа?";
echo "</div>";
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='main2'><center>";
echo "<a href='?park&amp;yes'><img src='img/yes.png' alt='Simptom'> Да</a>";
echo "</center></td>";
echo "<td class='main2'><center>";
echo "<a href='?'><img src='img/no.png' alt='Simptom'> Нет</a>";
echo "</center></td>";
echo "</tr></table>";
}
echo "<a href='?'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if (isset($_GET['gorod']))
{
echo "<div class='logo'><center>";
echo "<img src='img/logo_travel.png' alt='Simptom'>";
echo "</center></div>";
if (isset($_GET['yes']))
{
$t_3=time()+10800;
mysql_query("UPDATE `baby` SET `progulka` = '".$t_3."' WHERE `id` = '".$b['id']."'");
echo "<div class='msg'>";
echo "Вы успешно вывели своего ребёнка на прогулку в город!";
echo "</div>";
}else{
echo "<div class='err'>";
echo "Вы уверены что хотите вывести своего ребёнка на прогулку в город на 3 часа?";
echo "</div>";
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='main2'><center>";
echo "<a href='?gorod&amp;yes'><img src='img/yes.png' alt='Simptom'> Да</a>";
echo "</center></td>";
echo "<td class='main2'><center>";
echo "<a href='?'><img src='img/no.png' alt='Simptom'> Нет</a>";
echo "</center></td>";
echo "</tr></table>";
}
echo "<a href='?'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
?>