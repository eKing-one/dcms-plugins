<?
if ($_SERVER['PHP_SELF']=='/info.php') {
$fon_info = mysql_fetch_array(mysql_query("SELECT * FROM `fon_info` WHERE `user_id` = '".$ank['id']."'"));
if (isset($fon_info['user_id']) AND $fon_info['fon_id'] >= 1) {
$ost = $realtime - $fon_info['time'];
if ($ost > 604800) mysql_query("UPDATE `fon_info` SET `fon_id` = '0', `time` = '0' WHERE `user_id` = '".$ank['id']."'");
if ($fon_info['fon_id'] == 1)
{
echo '</div>';
}
if ($fon_info['fon_id'] == 2)
{
echo '</div>';
}
if ($fon_info['fon_id'] == 3)
{
echo '</div>';
}
if ($fon_info['fon_id'] == 4)
{
echo '</div>';
}
if ($fon_info['fon_id'] == 5)
{
echo '</div>';
}
if ($fon_info['fon_id'] == 6)
{
echo '</div>';
}
if ($fon_info['fon_id'] == 7)
{
echo '</div>';
}
if ($fon_info['fon_id'] == 8)
{
echo '</div>';
}
if ($fon_info['fon_id'] == 9)
{
echo '</div>';
}
}
}
?>