<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['user']) && isset($_GET['vopros']) && isset($_GET['otvet']))
{
if ($user['pol']==0)
{
$poll=1;
}else{
$poll=0;
}
$usser=intval($_GET['user']);
$vopros=intval($_GET['vopros']);
$otvet=intval($_GET['otvet']);
$use=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$usser."' LIMIT 1"));
$vop=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_voprosu` WHERE `id` = '".$vopros."' LIMIT 1"));
if (!$use || $use['id']==0 || $user['pol']==$poll || $user['id']==$use['id'] || $use['flirt_pokaz']==1 || !$vop || $vop['id']==0 || $otvet==0 || $otvet>=4)
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
mysql_query("INSERT INTO `flirt_lenta` (`id_user`, `id_ank`, `id_vopros`, `id_variant`, `time`) VALUES ('".$user['id']."', '".$use['id']."', '".$vop['id']."', '".$otvet."', '".$time."')");
if ($us['flirt_izv']==0)
{
if ($user['pol']==0)
{
$a='a';
}else{
$a='';
}
$msgs=''.$user['nick'].' ответил'.$a.' на вопрос о вас в игре Флиртомания! [url=/flirt/my_lenta.php]Подробнее[/url]';
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `time`, `msg`, `read`) VALUES ('0', '".$use['id']."', '".$time."', '".$msgs."', '0')");
}
header("Location: /flirt/index.php?".SID);
exit;
}
?>