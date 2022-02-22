<?
if ($_SERVER['PHP_SELF']=='/ank.php') {
$fon_anketa = mysql_fetch_array(mysql_query("SELECT * FROM `fon_anketa` WHERE `user_id` = '".$ank['id']."'"));
if (isset($fon_anketa['user_id']) AND $fon_anketa['fon_id'] >= 1) {
$ost = $realtime - $fon_anketa['time'];
if ($ost > 604800) mysql_query("UPDATE `fon_anketa` SET `fon_id` = '0', `time` = '0' WHERE `user_id` = '".$ank['id']."'");
if ($fon_anketa['fon_id'] == 1)
{
echo '</div>';
}
if ($fon_anketa['fon_id'] == 2)
{
echo '</div>';
}
if ($fon_anketa['fon_id'] == 3)
{
echo '</div>';
}
if ($fon_anketa['fon_id'] == 4)
{
echo '</div>';
}
if ($fon_anketa['fon_id'] == 5)
{
echo '</div>';
}
if ($fon_anketa['fon_id'] == 6)
{
echo '</div>';
}
if ($fon_anketa['fon_id'] == 7)
{
echo '</div>';
}
if ($fon_anketa['fon_id'] == 8)
{
echo '</div>';
}
if ($fon_anketa['fon_id'] == 9)
{
echo '</div>';
}
}
}
?>