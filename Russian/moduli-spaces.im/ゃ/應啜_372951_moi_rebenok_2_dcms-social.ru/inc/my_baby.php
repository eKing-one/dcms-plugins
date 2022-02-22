<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['rod']))
{
if ($b2)
{
echo "<div class='err'>";
echo "У вас уже есть ребёнок!";
echo "</div>";
echo "<a href='my_baby.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if ($za)
{
echo "<div class='err'>";
echo "Вы уже подавали заявку!";
echo "</div>";
echo "<a href='my_baby.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if ($b['papa']!=0 && $user['pol']==1)
{
echo "<div class='err'>";
echo "У этого ребёнка уже есть папа!";
echo "</div>";
echo "<a href='my_baby.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if ($b['mama']!=0 && $user['pol']==0)
{
echo "<div class='err'>";
echo "У этого ребёнка уже есть мама!";
echo "</div>";
echo "<a href='my_baby.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if ($user['pol']==0)
{
$rt='мамой';
}else{
$rt='папой';
}
if (isset($_GET['yes']))
{
if ($ank_1['id']!=0)
{
$us=$ank_1['id'];
}
else if ($ank_2['id']!=0)
{
$us=$ank_2['id'];
}
mysql_query("INSERT INTO `baby_sp` (`id_baby`, `id_user`, `time`) VALUES ('".$b['id']."', '".$user['id']."', '".time()."')");
$mgs="Обитатель ".$user['nick']." желает стать ".$rt." вашему ребёнку. [url=/baby/zayavki.php]Подробнее...[/url]";
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `time`, `msg`, `read`) VALUES ('0', '".$us."', '".$time."', '".$mgs."', '0')");
echo "<div class='msg'>";
echo "Заявка успешно отправлена, ожидайте ответа!";
echo "</div>";
}else{
echo "<div class='err'>";
echo "Вы уверены что хотите стать ".$rt." этому ребёнку!";
echo "</div>";
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='main2'><center>";
echo "<a href='?id=".$b['id']."&amp;rod&amp;yes'><img src='img/yes.png' alt='Simptom'> Да</a>";
echo "</center></td>";
echo "<td class='main2'><center>";
echo "<a href='?id=".$b['id']."'><img src='img/no.png' alt='Simptom'> Нет</a>";
echo "</center></td>";
echo "</tr></table>";
}
echo "<a href='my_baby.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
?>