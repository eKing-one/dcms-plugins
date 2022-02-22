<?php
$set['title'] = 'Сообщества'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();

$q=mysql_query("SELECT * FROM `comm_cat` ORDER BY `pos` ASC");
echo "<table class='post'>\n";
if(!mysql_num_rows($q))
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет категорий\n";
echo "</td>\n";
echo "</tr>\n";
}
while($post=mysql_fetch_array($q))
{
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo "<img src='/comm/img/cat.png'/>\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='?act=cat&id=$post[id]'>".htmlspecialchars($post['name'])."</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id_cat` = '$post[id]'"),0).")<span style='float:right'>".($user['level']>=3?"<a href='?act=edit_cat&id=$post[id]'><img src='/comm/img/edit.png'/></a>":NULL)."".($user['level']>=3?"<a href='?act=delete_cat&id=$post[id]'><img src='/comm/img/delete.png'/></a>":NULL)."</span><br/>".($post['desc']!=NULL?output_text($post['desc']).'<br/>':NULL);
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if (isset($user))
{
echo "<div class='foot'>\n";
echo "<img src='/comm/img/comm.png'/> <a href='?act=user&id=$user[id]'>Мои сообщества</a><br/>\n";
if($user['level']>=3)echo "<img src='/comm/img/add.png'/> <a href='?act=add_cat'>Добавить категорию</a><br/>\n";
echo "</div>\n";
}
?>