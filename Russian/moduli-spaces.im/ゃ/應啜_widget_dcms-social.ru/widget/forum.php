<?php


$adm_add=NULL;

$adm_add2=NULL;

if (!isset($user) || $user['level']==0){

$q222=mysql_query("SELECT * FROM `forum_f` WHERE `adm` = '1'");

while ($adm_f = mysql_fetch_assoc($q222))

{

$adm_add[]="`id_forum` <> '$adm_f[id]'";

}

if (sizeof($adm_add)!=0)

$adm_add2=' WHERE'.implode(' AND ', $adm_add);

}


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t`$adm_add2"),0);

$k_page=k_page($k_post,$set['p_str']);

$page=page($k_page);

$start=$set['p_str']*$page-$set['p_str'];
if (isset($user)){
if((isset($user['id']) && $user['post_forum'] == 0) or (!isset($user['id']))){
}else{ 

$q=mysql_query("SELECT * FROM `forum_t`$adm_add2 ORDER BY `time_create` DESC  LIMIT 3");

if (mysql_num_rows($q)==0) {

echo "  <div class='hide '>\n";
echo "Нет тем\n";
echo "  </div>\n";
}

while ($them = mysql_fetch_assoc($q))

{

echo '<div class="hide">';
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '$them[id_forum]' LIMIT 1"));

$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '$them[id_razdel]' LIMIT 1"));

//$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_t` WHERE `id` = '$post[id_them]' LIMIT 1"));

$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $them[id_user] LIMIT 1"));


if ($set['set_show_icon']==2){

echo "<img src='/style/themes/$set[set_them]/forum/48/them_$them[up]$them[close].png' />";

}

elseif ($set['set_show_icon']==1)

{

echo "<img src='/style/themes/$set[set_them]/forum/14/them_$them[up]$them[close].png' alt='' />";

}

echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/'><b><small>$them[name]</b></a> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/?page=end'>(".mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_them` = '$them[id]'"),0).")</a></br>\n";


$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $them[id_user] LIMIT 1"));



$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` DESC LIMIT 1"));

$ank2=get_user($post['id_user']);

if ($ank2['id'])echo " Посл.: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>$ank2[nick]</a> (".vremja($post['time']).")<br /></small>\n";

echo "  </div>\n";
}
}
}
?>