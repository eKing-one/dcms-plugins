<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
function ava_flirt($id)
{
$its=intval($id);
$anke=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '".$its."' LIMIT 1"));
$ava_u=mysql_fetch_array(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_user` = '".$anke['id']."' AND `avatar` = '1' LIMIT 1"));
if (is_file(H."sys/gallery/128/".$ava_u['id'].".".$ava_u['ras'].""))
{
echo "<img src='/foto/foto128/".$ava_u['id'].".".$ava_u['ras']."' alt='Simptom' height='80' />";
}else{
echo "<img src='img/ava.png' alt='Simptom' height='80'>";
}
}
function mini_ava_flirt($id)
{
$its=intval($id);
$anke=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '".$its."' LIMIT 1"));
$ava_u=mysql_fetch_array(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_user` = '".$anke['id']."' AND `avatar` = '1' LIMIT 1"));
if (is_file(H."sys/gallery/128/".$ava_u['id'].".".$ava_u['ras'].""))
{
echo "<img src='/foto/foto128/".$ava_u['id'].".".$ava_u['ras']."' alt='Simptom' height='50' />";
}else{
echo "<img src='img/ava.png' alt='Simptom' height='50'>";
}
}
?>