<?
//------------------alex-borisi----------------------//
function avatar($id){

global $set;
$p = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`='$id'  LIMIT 1"));
$a = mysql_fetch_array(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_user`='$id' AND `avatar`='1' LIMIT 1"));

if (is_file(H."sys/gallery/avatar/$a[id].$a[ras]"))
{
echo "<div style='display:inline;'><a href='/foto/$id/$a[id_gallery]/$a[id]/'><img  src='/foto/foto150/$a[id].$a[ras]' alt='$a[name]' class='avatar' align='' max-width='150' alt='naSIMbe.ru' ></a></div>";
$rat=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_rating` WHERE `id_foto` = $a[id] AND `like` = '6'"), 0);
if ($rat>0)echo "<div style='display:inline;margin-left:-25px;vertical-align:top;'><img style='padding-top:10px;' src='/style/icons/6.png'/></div>";
}
else
echo "<img style='vertical-align: middle; padding: 1px;' src='/foto/foto48/0.png' align=''   alt='naSIMbe.ru' style='margin-right:4px;border-right:px solid #99ccff'> ";
}
//-------------------the end-------------------------//

?>
