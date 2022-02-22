<?
$fon_anketa = mysql_fetch_array(mysql_query("SELECT * FROM `fon_anketa` WHERE `user_id` = '".$ank['id']."'"));
if (isset($fon_anketa['user_id']) AND $fon_anketa['fon_id'] >= 1)
{


if ($fon_anketa['fon_id'] == 1)
{
echo '<div class = "fon_anketa1">';
}
if ($fon_anketa['fon_id'] == 2)
{
echo '<div class = "fon_anketa2">';
}
if ($fon_anketa['fon_id'] == 3)
{
echo '<div class = "fon_anketa3">';
}
if ($fon_anketa['fon_id'] == 4)
{
echo '<div class = "fon_anketa4">';
}
if ($fon_anketa['fon_id'] == 5)
{
echo '<div class = "fon_anketa5">';
}
if ($fon_anketa['fon_id'] == 6)
{
echo '<div class = "fon_anketa6">';
}
if ($fon_anketa['fon_id'] == 7)
{
echo '<div class = "fon_anketa7">';
}
if ($fon_anketa['fon_id'] == 8)
{
echo '<div class = "fon_anketa8">';
}
if ($fon_anketa['fon_id'] == 9)
{
echo '<div class = "fon_anketa9">';
}
}
?>