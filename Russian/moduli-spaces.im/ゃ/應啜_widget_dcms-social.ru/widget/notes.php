<?
if (isset($user)){
if((isset($user['id']) && $user['post_notes'] == 0) or (!isset($user['id']))){
}else{ 
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `notes` "),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q=mysql_query("SELECT * FROM `notes` $order LIMIT 3");
if ($k_post==0)

{
echo "  <div class='hide'>\n";
echo "Нет записей\n";
echo "  </div>\n";
}

while ($post = mysql_fetch_assoc($q))
{

echo "  <div class='hide'>\n";
echo "<img src='/style/icons/dnev.png' alt='*'> ";
echo "<a href='list.php?id=$post[id]'><small>" . htmlspecialchars($post['name']) . "</a>";
echo " <span style='time'>(".vremja($post['time']).")</span></small>\n";


$k_n= mysql_result(mysql_query("SELECT COUNT(*) FROM `notes` WHERE `id` = $post[id] AND `time` > '$ftime'",$db), 0);

if ($k_n!=0)echo " <img src='/style/icons/new.gif' alt='*'>";

echo "  </div>\n";

}

}
}

?>