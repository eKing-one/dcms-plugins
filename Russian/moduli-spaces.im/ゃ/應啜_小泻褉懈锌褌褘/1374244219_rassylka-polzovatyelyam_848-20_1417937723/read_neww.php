<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
only_reg();

$set['title']='Новые уведомление от Администрации';
include_once 'sys/inc/thead.php';
mysql_query("UPDATE `user` SET `new_news_read` = '0' WHERE `id` >= '".$user['id']."' LIMIT 1");
title();
echo "<table class='post'>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `rassilka_send` WHERE `group_access` <= '$user[group_access]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='aut'>";
echo "Уведомлений нет\n";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `rassilka_send` WHERE `group_access` <= '$user[group_access]' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
echo "<div class='aut'>";
echo output_text($post['msg'])."<br />\n";
if($post['group_access']>1){
if($post['group_access']==1){ echo '[Юзерам]<br/>';}
else if($post['group_access']<=7){ echo '[Модерам]<br/>';}
else if($post['group_access']>=8){ echo '[Админам]<br/>';}
                            }
echo 'Отправлено: '.vremja($post['time']).'<hr/>';
echo "</div>";

}
echo "</div>\n";
if ($k_page>1)str("read_neww.php?act=read&amp;",$k_page,$page); // Вывод страниц


include_once 'sys/inc/tfoot.php';
?>