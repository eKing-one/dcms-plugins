<?
$k_post = mysql_result(mysql_query("SELECT COUNT(id) FROM `guest`"), 0);
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];
$limit=4; //количество выводимых собщений
$q=mysql_query("SELECT * FROM `guest` ORDER BY id DESC LIMIT $limit");
if (mysql_num_rows($q)!=0){
echo "<div class='main2'>";
echo '<img src="/style/icons/22222222222222.png"><a href="/guest/">Гостевая Портала  :</a>';echo "<span class='count'>";
echo '<a href="/guest/">Написать | </a>';
$k_p = mysql_result(mysql_query("SELECT COUNT(*) FROM `guest`",$db), 0);
$k_n = mysql_result(mysql_query("SELECT COUNT(*) FROM `guest` WHERE `time` > '" . $ftime . "'",$db), 0);
if ($k_n == 0)$k_n = NULL;
else $k_n = '+' . $k_n;
echo '(' . $k_p . ') <font color="red">' . $k_n . '</font>';
echo "</span>";
echo '</div>';


while ($post = mysql_fetch_assoc($q))
{

if ($post['id_user']==0)
{
$ank['id']=0;
$ank['pol']='guest';
$ank['level']=0;
}
else
$ank=get_user($post['id_user']);
//$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));

echo "  <div class='main2'>\n";
echo " ".group($ank['id'])."  <a href='/info.php?id=$ank[id]' title='Анкета $ank[nick]'>$ank[nick]</a>";

echo " ".medal($ank['id'])." ".online($ank['id'])."";
if (isset($user) && $user['id'] != $ank['id'])
echo ' (' . vremja($post['time']) . ')';
echo "   :   ";
echo output_text($post['msg'])."\n";
echo "<span class='count'>";
echo '<a href="/guest/?page=' . $page . '&response=' . $ank['id'] . '" title="Ответить В Гостевой"><img src="/sys/inc/g.png" alt="Ответить В Гостевой">';
echo "</span>";

echo '</div>';


}
echo "</table>\n";
}
?>






















