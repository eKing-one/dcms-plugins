<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Флиртомания';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
include_once 'inc/avatar.php';
include_once 'inc/index_act.php';
echo "<div class='p_t'>";
echo "<img src='img/flirtons.png' alt='Simptom'> <b>Флиртонов:</b> ".$user['flirt_flirtons']."";
echo "</div>";
if ($user['pol']==0)
{
$pol=1;
}else{
$pol=0;
}
$kol=mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `flirt_pokaz` = '0' AND `pol` = '".$pol."'"), 0);
if ($kol==0)
{
echo "<div class='err'><center>";
echo "Никого нету!";
echo "</center></div>";
echo "<div class='p_m'><center>";
echo "<img src='img/ava.png' width='80' alt='Simptom'>";
echo "</center></div>";
}
$a=mysql_query("SELECT * FROM `user` WHERE `flirt_pokaz` = '0' AND `pol` = '".$pol."' ORDER BY RAND() LIMIT 1");
while ($ank=mysql_fetch_assoc($a))
{
echo "<div class='p_t'><center>";
echo "".status($ank['id'])." ";
echo "<a href='/info.php?id=".$ank['id']."'>".$ank['nick']."</a> ";
echo "".online($ank['id'])."";
echo "</center></div>";
echo "<div class='p_m'><center>";
ava_flirt($ank['id']);
echo "</center></div>";
$kol_v=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt_voprosu`"), 0);
if ($kol_v==0)
{
echo "<div class='err'><center>";
echo "Вопросы отсуствуют!";
echo "</center></div>";
}
$voprosu=mysql_query("SELECT * FROM `flirt_voprosu` ORDER BY RAND() LIMIT 1");
while ($vopros=mysql_fetch_assoc($voprosu))
{
echo "<div class='p_t'>";
echo "<b>Вопрос:</b> ".output_text($vopros['vopros'])."";
echo "</div>";
echo "<a href='?user=".$ank['id']."&amp;vopros=".$vopros['id']."&amp;otvet=1'><div class='p_t'>";
echo "<img src='img/vop.png' alt='Simptom'> ".output_text($vopros['variant_1'])."";
echo "</div></a>";
echo "<a href='?user=".$ank['id']."&amp;vopos=".$vopros['id']."&amp;otvet=2'><div class='p_t'>";
echo "<img src='img/vop.png' alt='Simptom'> ".output_text($vopros['variant_2'])."";
echo "</div></a>";
echo "<a href='?user=".$ank['id']."&amp;vopros=".$vopros['id']."&amp;otvet=3'><div class='p_t'>";
echo "<img src='img/vop.png' alt='Simptom'> ".output_text($vopros['variant_3'])."";
echo "</div></a>";
}
echo "<a href='flirt.php?id=".$ank['id']."'><div class='foot'>";
echo "<img src='img/flirt.png' alt='Simptom'> Флиртовать с ".$ank['nick']."";
echo "</div></a>";
}
echo "<div class='title'>";
echo "<center>Навигация:</center>";
echo "</div>";
$flirt=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt_fl` WHERE `id_ank` = '".$user['id']."'"), 0);
echo "<a href='flirtu.php'><div class='foot'>";
echo "<img src='img/flirt.png' alt='Simptom'> Флирт (".$flirt.")";
echo "</div></a>";
$lenta=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt_lenta` WHERE `id_ank` = '".$user['id']."' AND `otvet` = '0'"), 0);
echo "<a href='my_lenta.php'><div class='foot'>";
echo "<img src='img/lenta.png' alt='Simptom'> Моя лента (".$lenta.")";
echo "</div></a>";
$top=mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `flirt_flirtons` > '0'"), 0);
echo "<a href='top.php'><div class='foot'>";
echo "<img src='img/top.png' alt='Simptom'> Рейтинг (".$top.")";
echo "</div></a>";
echo "<a href='setings.php'><div class='foot'>";
echo "<img src='img/setings.png' alt='Simptom'> Настройки";
echo "</div></a>";
echo "<a href='info.php'><div class='foot'>";
echo "<img src='img/info.png' alt='Simptom'> Информация";
echo "</div></a>";
if ($user['level']>=4)
{
echo "<a href='admin.php'><div class='foot'>";
echo "<img src='img/admin.png' alt='Simptom'> Админка";
echo "</div></a>";
}
include_once '../sys/inc/tfoot.php';
?>