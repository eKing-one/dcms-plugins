<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['flirt']))
{
$fit=intval($_GET['flirt']);
$flirt=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_flirt` WHERE `id` = '".$fit."' LIMIT 1"));
if (!$flirt || $flirt['id']==0)
{
echo "<div class='err'>";
echo "Ошибка!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if ($user['flirt_flirtons']<$flirt['cena'])
{
echo "<div class='err'>";
echo "У а недостатоно флиртонов!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
mysql_query("INSERT INTO `flirt_fl` (`id_user`, `id_ank`, `id_flirt`, `time`) VALUES ('".$user['id']."', '".$ank['id']."', '".$flirt['id']."', '".$time."')");
$fff=$user['flirt_flirtons']-$flirt['cena'];
mysql_query("UPDATE `user` SET `flirt_flirtons` = '".$fff."' WHERE `id` = '".$user['id']."' LIMIT 1");
if ($ank['flirt_izv']==0)
{
$msgs=''.$user['nick'].' флиртует с вами! [url=/flirt/flirtu.php]Подробнее[/url]';
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `time`, `msg`, `read`) VALUES ('0', '".$ank['id']."', '".$time."', '".$msgs."', '0')");
}
echo "<div class='msg'>";
echo "Успешно!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
?>