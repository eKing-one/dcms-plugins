<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['igrushki']))
{
echo "<a href='add_igrushki.php'><div class='main2'>";
echo "<img src='img/add.png' alt='Simptom'> Добавить";
echo "</div></a>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_shop_igrushki`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Пусто";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `baby_shop_igrushki` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14' style='width:10%'><center>";
echo "<img src='shop_igrushki/".$post['id'].".png' alt='Simptom'>";
echo "</center></td>";
echo "<td class='main'>";
echo "<img src='img/shop.png' alt='Simptom'> ".$post['name']."<br />";
echo "<img src='img/cena.png' alt='Simptom'> ".$post['cena']." баллов<br />";
echo "<a href='edit_igrushki.php?id=".$post['id']."'><img src='img/edit.png' alt='Simptom'> Редактировать</a><br />";
echo "<a href='del_igrushki.php?id=".$post['id']."'><img src='img/del.png' alt='Simptom'> Удалить</a><br />";
echo "</td>";
echo "</tr></table>";
}
if ($k_page>1)
{
str('?igrushki&amp;',$k_page,$page);
}
echo "<a href='shop.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if (isset($_GET['eda']))
{
echo "<a href='add_eda.php'><div class='main2'>";
echo "<img src='img/add.png' alt='Simptom'> Добавить";
echo "</div></a>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_shop_eda`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Пусто";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `baby_shop_eda` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14' style='width:10%'><center>";
echo "<img src='shop_eda/".$post['id'].".png' alt='Simptom'>";
echo "</center></td>";
echo "<td class='main'>";
echo "<img src='img/shop.png' alt='Simptom'> ".$post['name']."<br />";
echo "<img src='img/cena.png' alt='Simptom'> ".$post['cena']." баллов<br />";
echo "<img src='img/health.png' alt='Simptom'> +".$post['health']."% здоровья и сытости<br />";
echo "<a href='edit_eda.php?id=".$post['id']."'><img src='img/edit.png' alt='Simptom'> Редактировать</a><br />";
echo "<a href='del_eda.php?id=".$post['id']."'><img src='img/del.png' alt='Simptom'> Удалить</a><br />";
echo "</td>";
echo "</tr></table>";
}
if ($k_page>1)
{
str('?eda&amp;',$k_page,$page);
}
echo "<a href='shop.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
else if (isset($_GET['skazki']))
{
echo "<a href='add_skazki.php'><div class='main2'>";
echo "<img src='img/add.png' alt='Simptom'> Добавить";
echo "</div></a>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_shop_skazki`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Пусто";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `baby_shop_skazki` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14' style='width:10%'><center>";
echo "<img src='img/skazki.png' alt='Simptom'>";
echo "</center></td>";
echo "<td class='main'>";
echo "<img src='img/shop.png' alt='Simptom'> ".$post['name']."<br />";
echo "<img src='img/cena.png' alt='Simptom'> ".$post['cena']." баллов<br />";
echo "<a href='edit_skazki.php?id=".$post['id']."'><img src='img/edit.png' alt='Simptom'> Редактировать</a><br />";
echo "<a href='del_skazki.php?id=".$post['id']."'><img src='img/del.png' alt='Simptom'> Удалить</a><br />";
echo "</td>";
echo "</tr></table>";
}
if ($k_page>1)
{
str('?skazki&amp;',$k_page,$page);
}
echo "<a href='shop.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
?>