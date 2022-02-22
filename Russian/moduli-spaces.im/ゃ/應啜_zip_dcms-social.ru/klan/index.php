<?php
########################################
#   Мод кланы для DCMS for VIPTABOR    #
#      Автор: DenSBK ICQ: 830-945	   #
#  Запрещена перепродажа данного мода. #
# Запрещено бесплатное распространение #
#    Все права пренадлежат автору      #
########################################

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$set['title'] = 'Кланы';
include_once '../sys/inc/thead.php';

title();
aut();

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `clan`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='str'>";
echo "Кланы еще не созданы<br/>\n";
echo "</div>"; 
}

$q = mysql_query("SELECT * FROM `clan` ORDER BY `level` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
if($num==1){ 
echo "<div class='rowup'>"; 
$num=0;
}else{ 
echo "<div class='rowdown'>"; 
$num=1;}

if(is_file(H.'files/klan/'.$post['id'].'.jpg')){
echo '<img src="/files/klan/'.$post['id'].'.jpg" align="left" width="47" height="49" alt=""/>';
}
else
{
echo '<img src="/files/klan/0.png" align="left" width="47" height="49" alt=""/>';
}

if (mysql_result(mysql_query("SELECT COUNT(id) FROM `clan_user` WHERE `id_clan`= '$post[id]' AND `id_user` = '$user[id]' AND `activaty` = '0' LIMIT 1"),0)!=0)echo "<a href='myklan.php'>$post[name]</a>\n";
else echo "<a href='klan.php?id=$post[id]'>$post[name]</a>\n";
if(is_file(H.'files/klanico/'.$post['id'].'.jpg')){
echo '<img src="/files/klanico/'.$post['id'].'.jpg" alt=""/>';
}
echo "<br/>\n";
$users = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_clan` = '$post[id]' AND `activaty` = 0"),0);
$level = ($post['level']*5)*$users;
echo "Рейтинг: <strong>$level</strong><br/>\n";
echo "Юзеров: <strong>".$users."</strong><br /><br />\n";

echo "</div>"; 
}

if ($k_page>1)str("?",$k_page,$page); // Вывод страниц;

echo '<div class="str">';
if ($user['balls']>=1000 && mysql_result(mysql_query("SELECT COUNT(id) FROM `clan_user` where `id_user` = '$user[id]' LIMIT 1"),0)==0)
{
echo '<a href="create.php">Создать Клан</a><br/>';
}
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo '</div>';
include_once '../sys/inc/tfoot.php';
?>