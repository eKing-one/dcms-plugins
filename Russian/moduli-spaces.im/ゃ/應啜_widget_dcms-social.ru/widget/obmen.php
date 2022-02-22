<? 
if (isset($user)){
if((isset($user['id']) && $user['post_obmen'] == 0) or (!isset($user['id']))){
}else{ 
if(!isset($user) or $setting['obmen_set'] == NULL)$setting['obmen_set'] = 3;

$q = mysql_query("SELECT * FROM `obmennik_files` WHERE `id_dir` != '$my_dir[id]' ORDER BY `time` DESC LIMIT ".$setting['obmen_set']."");
while ($post = mysql_fetch_array($q))
{


$ras=$post['ras'];
$file=H."sys/obmen/files/$post[id].dat";
$name=$post['name'];
$size=$post['size'];


echo "<div class='block'>";
include 'obmen/inc/icon14.php';

$dir = mysql_fetch_array(mysql_query("SELECT * FROM `obmennik_dir` WHERE `id` = '$post[id_dir]' LIMIT 1"));

if ($set['echo_rassh']==1)$ras=".$post[ras]";else $ras=NULL;

echo "<a href='/obmen$dir[dir]$post[id].$post[ras]?showinfo'><small>".htmlspecialchars($post["name"])."$ras</a>\n";


echo '('.size_file($size).") <br />\n";
 $ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$post[id_user]' LIMIT 1")); 
echo "Выгрузил: <a href='/id$ank[id]'>$ank[nick]</a> (". vremja($post['time']).")</small>\n";

echo'</div>';
}
}}
?>