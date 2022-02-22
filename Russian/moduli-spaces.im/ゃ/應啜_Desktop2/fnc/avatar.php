<?

function avatar($id)
{
global $set;
if (is_file(H."sys/avatar/$id.gif"))
echo "<img src='/sys/avatar/$id.gif' alt='' />\n";
elseif (is_file(H."sys/avatar/$id.jpg"))
echo "<img src='/sys/avatar/$id.jpg' alt='' />\n";
elseif (is_file(H."sys/avatar/$id.png"))
echo "<img src='/sys/avatar/$id.png' alt='' />\n";
elseif ($_SERVER['PHP_SELF']!='')
echo "<img src='/style/themes/$set[set_them]/user.png' alt='' />\n";


if ($_SERVER['PHP_SELF']=='/info.php' && (is_file(H."sys/avatar/$id.gif") || is_file(H."sys/avatar/$id.jpg") || is_file(H."sys/avatar/$id.png")))echo "<br />\n";
}
?>