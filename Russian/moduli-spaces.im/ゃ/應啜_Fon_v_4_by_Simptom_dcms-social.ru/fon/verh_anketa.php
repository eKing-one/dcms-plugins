<?
include_once 'fon/fon.php';
if (is_file(H."fon/img_anketa/$ank[id].png"))
{
echo "<div class = 'fon_anketa1'>";
}
if (is_file(H."fon/img_anketa/$ank[id].jpg"))
{
echo "<div class = 'fon_anketa2'>";
}
if (is_file(H."fon/img_anketa/$ank[id].gif"))
{
echo "<div class = 'fon_anketa3'>";
}
?>