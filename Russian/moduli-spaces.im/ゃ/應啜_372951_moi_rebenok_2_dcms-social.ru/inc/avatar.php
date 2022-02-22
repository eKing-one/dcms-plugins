<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
function ava_baby($id)
{
$it=intval($id);
$ava=mysql_fetch_array(mysql_query("SELECT * FROM `baby` WHERE `id` = '".$it."' LIMIT 1"));
if (is_file("avatar/".$ava['id'].".png"))
{
echo "<img src='avatar/".$ava['id'].".png' width='70' alt='Simptom'>";
}else{
echo "<img src='img/b_".$ava['pol'].".png' width='70' alt='Simptom'>";
}
}
?>