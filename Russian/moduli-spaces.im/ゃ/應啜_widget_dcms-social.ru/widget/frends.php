<?
if (isset($user)){
if((isset($user['id']) && $user['post_frends'] == 0) or (!isset($user['id']))){
}else{ 
if (isset($_GET['id']))$sid = intval($_GET['id']);
else $sid = $user['id'];
$ank = get_user($sid);
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` INNER JOIN `user` ON `frends`.`frend`=`user`.`id` WHERE `frends`.`user` = '$ank[id]' AND `frends`.`i` = '1' AND `user`.`date_last`>'".(time()-600)."'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `frends` INNER JOIN `user` ON `frends`.`frend`=`user`.`id` WHERE `frends`.`user` = '$ank[id]' AND `frends`.`i` = '1' AND `user`.`date_last`>'".(time()-600)."' ORDER BY `user`.`date_last` DESC LIMIT 5");
echo '<div class="block">';
if ($k_post==0)
{
echo '<small>У вас нет друзей которые в сети</small>';
}
while ($frend = mysql_fetch_assoc($q))
{
$frend=get_user($frend['frend']);



if ($set['set_show_icon']==2){

}
elseif ($set['set_show_icon']==1)
{

echo "".friends($frend['id'])." ";
}
}
echo '</div>';
}
}

?>