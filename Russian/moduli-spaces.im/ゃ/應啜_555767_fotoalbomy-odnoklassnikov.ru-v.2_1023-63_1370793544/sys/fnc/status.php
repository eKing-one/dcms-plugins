<?
//------------------alex-borisi----------------------//
function status($id){

global $set;
$p = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`='$id'  LIMIT 1"));
$a = mysql_fetch_array(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_user`='$id' AND `avatar`='1' LIMIT 1"));

if (is_file(H."sys/gallery/50/$a[id].$a[ras]"))
{
echo "<img  src='/foto/foto50/$a[id].$a[ras]' alt='$a[name]' class='avatar' alt='*' >";
}
else
echo "<img style='vertical-align: middle; padding: 1px;' src='/foto/foto48/0.png' align=''   alt='naSIMbe.ru' style='margin-right:4px;border-right:px solid #99ccff'> ";
}
//-------------------the end-------------------------//
?>
