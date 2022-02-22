<?php

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
Error_Reporting(E_ALL & ~E_NOTICE);
$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"));
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$us[id_clan]' LIMIT 1"));

$set['title'] = 'Чат клана '.$clan['name'];
include_once '../sys/inc/thead.php';
title();

if (mysql_result(mysql_query("SELECT COUNT(id) FROM `clan_user` where `id_user` = '$user[id]' AND `activaty` = '0' LIMIT 1"),0)==0){
echo "<div class=\"str\">\n";
echo "Вы не состоите в клане\n";
echo "</div>\n";
}
else
{
if($us['level']>0){
if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)>512){$err='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_chat` WHERE `id_user` = '$user[id]' AND `id_clan` = '$clan[id]' AND `msg` = '".mysql_escape_string($msg)."' AND `time` > '".($time-300)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
else{
$msg=mysql_escape_string($msg);
mysql_query("INSERT INTO `clan_chat` (`id_user`, `time`, `msg`, `id_clan`) values('$user[id]', '$time', '$msg', '$clan[id]')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
msg('Сообщение успешно добавлено');
}
}

echo "<a href='?act=chat&amp;".rand(1000,9999)."'>Обновить</a><br />\n";
if (isset($user))
{
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '".intval($_GET['reply'])."' LIMIT 1"));
$ank['nick'] = isset($ank['nick']) ? mysql_escape_string($ank['nick']).',' : '';

echo "<form method=\"post\" name='message' action=\"?act=chat\">\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\">$ank[nick]</textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input value=\"Отправить\" type=\"submit\" />\n";
echo "</form>\n";
}

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_adm_chat` WHERE `id_clan` = '$clan[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='rowdown'>\n";
echo "Нет сообщений\n";
echo "  </td>\n";
echo "   </tr>\n";
}

$q=mysql_query("SELECT * FROM `clan_adm_chat` WHERE `id_clan` = '$clan[id]' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));

if(@$num==1){
echo "<div class='rowdown'>";
@$num=0;
}else{
echo "<div class='rowup'>";
@$num=1;}

/*-----------зебра-----------*/
if ($num==0){
	echo "  <div class='nav1'>\n";
	$num=1;
}elseif ($num==1){
	echo "  <div class='nav2'>\n";
	$num=0;
}
/*---------------------------*/

if ($set['set_show_icon']==2){
	avatar($ank['id']);
}
elseif ($set['set_show_icon']==1)
{
	echo "".status($ank['id'])."";
}


echo group($ank['id'])." <a href='/info.php?id=$ank[id]'>$ank[nick]</a>\n";
echo "".medal($ank['id'])." ".online($ank['id'])."";
echo "<br/>";
if ($ank['ank_city']!=0)echo ",".oncity($ank['id'])."";

if(isset($user) && $us['level']==2){
echo "<a href='adminka.php?act=delpost&amp;id=$post[id_clan]&amp;del=$post[id]'><font color = orange>DEL</font></a><br />\n";
}
if(isset($user) && $us['level']==1){
echo "<a href='moderka.php?act=delpost&amp;id=$post[id_clan]&amp;del=$post[id]'><font color = orange>DEL</font></a><br />\n";
}
echo "<br?\n";
echo "<span>\n";
echo esc(trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($post['msg']))))))))."\n";
echo "</span><br/>\n";
echo "<a href='?reply=$post[id_user]&amp;".rand(1000,9999)."'>Ответить</a><br />\n";
echo "</div>";
}
if ($k_page>1)str("?",$k_page,$page); // Вывод страниц
}
else
{
echo "<div class='rowdown'>\n"; 
echo "Вы не являетесь членом админ состава данного клана!\n";  
echo "</div>\n";
}
}
echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>