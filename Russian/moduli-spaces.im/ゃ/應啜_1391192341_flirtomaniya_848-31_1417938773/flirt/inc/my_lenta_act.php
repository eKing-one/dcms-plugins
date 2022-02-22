<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['vopros']) && isset($_GET['otvet']))
{
$vopros=intval($_GET['vopros']);
$otvet=intval($_GET['otvet']);
$vop=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_lenta` WHERE `id` = '".$vopros."' LIMIT 1"));
if (!$vop || $vop['id']==0 || $vop['otvet']!=0 || $vop['id_ank']!=$user['id'] || $otvet==0 || $otvet>=4)
{
echo "<div class='err'>";
echo "Ошибка!";
echo "</div>";
echo "<a href='my_lenta.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
$anke=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$vop['id_user']."' LIMIT 1"));
if ($otvet==1 && $vop['id_variant']==1)
{
$bba_1=$user['flirt_flirtons']+1;
mysql_query("UPDATE `user` SET `flirt_flirtons` = '".$bba_1."' WHERE `id` = '".$user['id']."' LIMIT 1");
$bba_2=$anke['flirt_flirtons']+1;
mysql_query("UPDATE `user` SET `flirt_flirtons` = '".$bba_2."' WHERE `id` = '".$anke['id']."' LIMIT 1");
}
else if ($otvet==2 && $vop['id_variant']==2)
{
$bba_1=$user['flirt_flirtons']+1;
mysql_query("UPDATE `user` SET `flirt_flirtons` = '".$bba_1."' WHERE `id` = '".$user['id']."' LIMIT 1");
$bba_2=$anke['flirt_flirtons']+1;
mysql_query("UPDATE `user` SET `flirt_flirtons` = '".$bba_2."' WHERE `id` = '".$anke['id']."' LIMIT 1");
}
else if ($otvet==3 && $vop['id_variant']==3)
{
$bba_1=$user['flirt_flirtons']+1;
mysql_query("UPDATE `user` SET `flirt_flirtons` = '".$bba_1."' WHERE `id` = '".$user['id']."' LIMIT 1");
$bba_2=$anke['flirt_flirtons']+1;
mysql_query("UPDATE `user` SET `flirt_flirtons` = '".$bba_2."' WHERE `id` = '".$anke['id']."' LIMIT 1");
}
mysql_query("UPDATE `flirt_lenta` SET `otvet` = '".$otvet."' WHERE `id` = '".$vop['id']."' LIMIT 1");
if ($anke['flirt_izv']==0)
{
if ($user['pol']==0)
{
$a='a';
}else{
$a='';
}
$msgs=''.$user['nick'].' ответил'.$a.' на вопрос в игре Флиртомания! [url=/flirt/otvet.php?id='.$vop['id'].']Подробнее[/url]';
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `time`, `msg`, `read`) VALUES ('0', '".$anke['id']."', '".$time."', '".$msgs."', '0')");
}
header("Location: /flirt/otvet.php?id=".$vop['id']."");
exit;
}
?>