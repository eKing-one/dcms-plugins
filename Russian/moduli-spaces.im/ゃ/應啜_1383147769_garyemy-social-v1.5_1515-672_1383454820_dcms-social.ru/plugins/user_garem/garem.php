<?
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_user` = $ank[id]"),0)==1){
   $cms_g=mysql_fetch_assoc(mysql_query("SELECT * FROM `cms_garem` WHERE `id_user` = $ank[id] LIMIT 1"));
   $cms_user = get_user($cms_g['id_garem']);
   echo "<div class='".($set['web']==true?'main':'nav2')."'><img src='/style/icons/garem_k.gif' alt=''> В гареме у <a href='/id$cms_user[id]'>$cms_user[nick]</a>";

  if ($user['id']==$ank['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_user` = $user[id]"),0)==1)
   echo " [<a href='/plugins/user_garem/exit/'>Выкупится</a>]"; 
   echo "</div>";
}
//******************************************************************************
    $garem_c = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_garem`= $ank[id]"),0);
    echo "<div class='".($set['web']==true?'main':'nav2')."'><img src='/style/icons/garem.png' alt=''> <a href='/plugins/user_garem/id-$ank[id]/'>Гарем</a> ($garem_c)</div>\n";
    
if (isset($user) && $user['pol'] != $ank['pol'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_user`= '$ank[id]' AND `id_garem`= '$user[id]'"),0)==0)
echo "<div class='".($set['web']==true?'main':'nav2')."'><img src='/style/icons/garem_k.gif' alt=''> <a href='/plugins/user_garem/id-$ank[id]/kupit/'>Украсть к себе в гарем</a></div>\n";