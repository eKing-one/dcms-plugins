<?
function avatarvip($id){
	global $set;
	$p = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`='$id'  LIMIT 1"));
	$a = mysql_fetch_array(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_user`='$id' AND `avatar`='1' LIMIT 1"));
	if (is_file(H."sys/gallery/50/$a[id].$a[ras]"))
	echo "<a href='/foto/$id/$a[id_gallery]/$a[id]/'><img src='/foto/foto50/$a[id].$a[ras]' alt='$a[name]' alt='naSIMbe.ru'></a>";
	else
	echo "<img src='/style/icons/avatar.png' alt='avatar' width='50' alt='naSIMbe.ru'>";
}
?>