<?
$fon_info = mysql_fetch_array(mysql_query("SELECT * FROM `fon_info` WHERE `user_id` = '".$ank['id']."'"));
if (isset($fon_info['user_id']) AND $fon_info['fon_id'] >= 1) 
{

if ($fon_info['fon_id'] == 1)
{
echo '<div class = "fon_info1">';
}
if ($fon_info['fon_id'] == 2)
{
echo '<div class = "fon_info2">';
}
if ($fon_info['fon_id'] == 3)
{
echo '<div class = "fon_info3">';
}
if ($fon_info['fon_id'] == 4)
{
echo '<div class = "fon_info4">';
}
if ($fon_info['fon_id'] == 5)
{
echo '<div class = "fon_info5">';
}
if ($fon_info['fon_id'] == 6)
{
echo '<div class = "fon_info6">';
}
if ($fon_info['fon_id'] == 7)
{
echo '<div class = "fon_info7">';
}
if ($fon_info['fon_id'] == 8)
{
echo '<div class = "fon_info8">';
}
if ($fon_info['fon_id'] == 9)
{
echo '<div class = "fon_info9">';
}
}
?>