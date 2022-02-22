<?
if (!isset($user)){
echo '<a class="touch1" href="/sait?"><img class="middle" src="/img/about.gif"> Первый раз на сайте ?</a>';
}
if (isset($user)){
if((isset($user['id']) && $user['post_liders'] == 0) or (!isset($user['id']))){

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `liders` WHERE `time` > '$time'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_lider > 0){	
}
{
echo '<div class="block">';
$q=mysql_query("SELECT * FROM `liders` WHERE `time` > '$time' ORDER BY stav DESC LIMIT 5");
while ($post = mysql_fetch_assoc($q))
{
$ank=get_user($post['id_user']);
echo friends($ank['id']); // Аватарка
}
}
echo'<a href="/user/liders/"><img src="/style/icons/11.png" width="25"/></a>';
echo'</div>';		
}
}



?>