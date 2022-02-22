<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `id` = '".$it."' LIMIT 1"));
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
if (!$ank || $ank['id']==0)
{
echo "<div class='err'>";
echo "<b>Ребёнок не найден!</b>";
echo "</div>";
echo "<a href='det_dom.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['my']))
{
if ($b)
{
echo "<div class='err'>";
echo "У вас есть ребёнок!";
echo "</div>";
echo "<a href='det_dom.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if ($ank['mama']!=0 && $ank['papa']!=0)
{
echo "<div class='err'>";
echo "Этого ребёнка нет в детдоме!";
echo "</div>";
echo "<a href='det_dom.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if ($user['pol']==0)
{
$rod='мамой';
}else{
$rod='папой';
}
if (isset($_GET['yes']))
{
if ($user['pol']==0)
{
mysql_query("UPDATE `baby` SET `mama` = '".$user['id']."' WHERE `id` = '".$ank['id']."' LIMIT 1");
}else{
mysql_query("UPDATE `baby` SET `papa` = '".$user['id']."' WHERE `id` = '".$ank['id']."' LIMIT 1");
}
mysql_query("UPDATE `baby` SET `time` = '".time()."' WHERE `id` = '".$ank['id']."' LIMIT 1");
mysql_query("UPDATE `baby` SET `health` = '100' WHERE `id` = '".$ank['id']."' LIMIT 1");
mysql_query("UPDATE `baby` SET `happy` = '100' WHERE `id` = '".$ank['id']."' LIMIT 1");
mysql_query("UPDATE `baby` SET `eda` = '100' WHERE `id` = '".$ank['id']."' LIMIT 1");
mysql_query("UPDATE `baby` SET `health_time` = '".time()."' WHERE `id` = '".$ank['id']."' LIMIT 1");
mysql_query("UPDATE `baby` SET `happy_time` = '".time()."' WHERE `id` = '".$ank['id']."' LIMIT 1");
mysql_query("UPDATE `baby` SET `eda_time` = '".time()."' WHERE `id` = '".$ank['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "<b>Поздравлем, вы стали ".$rod."</b>";
echo "</div>";
echo "<a href='my_baby.php'><div class='main2'><center>";
echo "К ребенку";
echo "</center></div></a>";
}else{
echo "<div class='err'>";
echo "<b>Вы уверены что хотите стать ".$rod." этому ребёнку?</b>";
echo "</div>";
echo "<a href='?id=".$ank['id']."&amp;my&amp;yes'><div class='main2'><center>";
echo "Да, стать ".$rod."";
echo "</center></div></a>";
}
echo "<a href='det_dom.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['del']) && $user['level']>=4)
{
if (isset($_GET['yes']))
{
unlink(H."baby/avatar/".$ank['id'].".png");
mysql_query("DELETE FROM `baby` WHERE `id` = '".$ank['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "<b>Информация о ребёнке успешно удалена!</b>";
echo "</div>";
}else{
echo "<div class='err'>";
echo "<b>Вы уверены что хотите удалить инфрмацию о данном ребёнке?</b>";
echo "</div>";
echo "<a href='?id=".$ank['id']."&amp;del&amp;yes'><div class='main2'><center>";
echo "Да, удалить";
echo "</center></div></a>";
}
echo "<a href='det_dom.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
}
?>