<?
if((isset($user['id']) && $user['post_rank'] == 0) or (!isset($user['id']))){
}else{ 
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];


$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `rank` WHERE `id_user` = '$ank[id]'"), 0);

if ($k_post > 0)

{


$q=mysql_query("SELECT * FROM `rank` WHERE `id_user` = '$ank[id]' ORDER BY `time` DESC LIMIT 5");
echo"<div class='hide'>Награды | ";
echo "<a href=\"rank/index.php?id=$ank[id]\">Все награды</a></div><div class='block'>";
}

while($post=mysql_fetch_array($q))
{


$ank2=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$post[ot_id]' LIMIT 1"));
$rank=mysql_fetch_array(mysql_query("SELECT * FROM `rank_list` WHERE `id` = '$post[id_rank]' LIMIT 1"));


echo " <img src='/rank/img_rank/$rank[id].png' width='15'>";

}

echo"</div>";
}



?>