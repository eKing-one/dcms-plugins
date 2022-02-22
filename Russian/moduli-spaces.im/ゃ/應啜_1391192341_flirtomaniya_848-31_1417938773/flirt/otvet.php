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
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$vo=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_lenta` WHERE `id` = '".$it."' LIMIT 1"));
$vop=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_voprosu` WHERE `id` = '".$vo['id_vopros']."' LIMIT 1"));
$ank_1=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$vo['id_user']."' LIMIT 1"));
$ank_2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$vo['id_ank']."' LIMIT 1"));
}
if (!isset($_GET['id']) || !$vo || $vo['id']==0 || ($user['id']!=$ank_1['id'] && $user['id']!=$ank_2['id']))
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
echo "<div class='p_t'>";
echo "<b>Вопрос:</b> ".output_text($vop['vopros'])."<br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Ответ ".$ank_1['nick'].":</b> ";
if ($vo['otvet']==0)
{
if ($user['id']==$ank_1['id'])
{
if ($vo['id_variant']==1)
{
echo "".output_text($vop['variant_1'])."";
}
else if ($vo['id_variant']==2)
{
echo "".output_text($vop['variant_2'])."";
}
else if ($vo['id_variant']==3)
{
echo "".output_text($vop['variant_3'])."";
}
}else{
echo "Будет доступно после вашего ответа!";
}
}else{
if ($vo['id_variant']==1)
{
echo "".output_text($vop['variant_1'])."";
}
else if ($vo['id_variant']==2)
{
echo "".output_text($vop['variant_2'])."";
}
else if ($vo['id_variant']==3)
{
echo "".output_text($vop['variant_3'])."";
}
}
echo "<hr />";
echo "<b>Ответ ".$ank_2['nick'].":</b> ";
if ($vo['otvet']!=0)
{
if ($vo['otvet']==1)
{
echo "".output_text($vop['variant_1'])."";
}
else if ($vo['otvet']==2)
{
echo "".output_text($vop['variant_2'])."";
}
else if ($vo['otvet']==3)
{
echo "".output_text($vop['variant_3'])."";
}
}else{
echo "Ожидаем ответ!";
}
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>