<?
function avatar($id){
global $set;
$ava = mysql_fetch_array(mysql_query("SELECT `id` FROM `albums_foto` WHERE `id_u`='$id' AND `avatar`='1' LIMIT 1"));
if (is_file(H.'albums/pictures/size128/'.$ava['id'].'.jpg')) echo '<img  src="/albums/pictures/size128/'.$ava['id'].'.jpg">';	
else	echo '<img src="/albums/pictures/noava.png" width="128">';
if ($_SERVER['PHP_SELF']=='/info.php')echo '<br />';}
?>