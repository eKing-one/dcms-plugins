<?
if (isset($user)){
if((isset($user['id']) && $user['post_foto'] == 0) or (!isset($user['id']))){
}else{ 
$sql = mysql_query("SELECT * FROM `gallery_foto` WHERE `id_user` = '$ank[id]' ORDER BY `id` DESC LIMIT 4");
$coll=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '$ank[id]' ORDER BY `id` DESC"),0);if ($coll>0){

echo "<div class='block'>";
	
	while ($photo = mysql_fetch_assoc($sql)){
echo "<span style='border-radius:10px '><a href='/foto/$ank[id]/$photo[id_gallery]/$photo[id]/'><img class='avatar' src='/foto/foto50/$photo[id].$photo[ras]' alt=''  width='40'/></a></span>";
}

}

	echo "</div>";

}
}

?>