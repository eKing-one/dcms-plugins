<?
include_once 'fon/fon.php';
if (is_file(H."fon/img_info/$ank[id].png"))
{
echo "<div class = 'fon_info1'>";
}
if (is_file(H."fon/img_info/$ank[id].jpg"))
{
echo "<div class = 'fon_info2'>";
}
if (is_file(H."fon/img_info/$ank[id].gif"))
{
echo "<div class = 'fon_info3'>";
}
?>