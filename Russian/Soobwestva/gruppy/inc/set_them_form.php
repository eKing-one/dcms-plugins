<?
if (isset($_GET['act']) && $_GET['act']=='mesto' && isset($user) && ($user['id']==$gruppy['admid'] || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1))
{
echo "<form method=\"post\" action=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=mesto&amp;ok\">\n";
echo "Форум:<br />\n";

echo "<select name=\"forum\">\n";


$q2 = mysql_query("SELECT * FROM `gruppy_forums` WHERE `id_gruppy` = '$gruppy[id]' ORDER BY `name` ASC");
while ($forums = mysql_fetch_assoc($q2))
{
echo "<option".($forum['id']==$forums['id']?' selected="selected"':null)." value='$forums[id]'>$forums[name]</option>\n";
}
echo "</select><br />\n";

echo "<input value=\"Переместить\" type=\"submit\" /><br />\n";
echo "&laquo;<a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]'>Отмена</a><br />\n";
echo "</form>\n";
}

if (isset($_GET['act']) && $_GET['act']=='set' && isset($user) && ($user['id']==$gruppy['admid'] || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1))
{
echo "<form method='post' action='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=set&amp;ok'>\n";
echo "Название темы:<br />\n";
echo "<input name='name' type='text' maxlength='32' value='$them[name]' /><br />\n";
if ($user['set_translit']==1)echo "<label><input type='checkbox' name='translit1' value='1' /> Транслит</label><br />\n";


if ($user['id']==$gruppy['admid']){
if ($them['up']==1)$check=' checked="checked"';else $check=NULL;
echo "<label><input type=\"checkbox\"$check name=\"up\" value=\"1\" /> Всегда наверху</label><br />\n";
}
if ($them['close']==1)$check=' checked="checked"';else $check=NULL;
echo "<label><input type=\"checkbox\"$check name=\"close\" value=\"1\" /> Закрыть</label><br />\n";


echo "<input value=\"Изменить\" type=\"submit\" /><br />\n";
echo "&laquo;<a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]'>Отмена</a><br />\n";
echo "</form>\n";
}

if (isset($_GET['act']) && $_GET['act']=='del' && isset($user) && $user['id']==$gruppy['admid'])
{
echo "<div class=\"err\">\n";
echo "Подтвердите удаление темы<br />\n";
echo "<a href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=delete&amp;ok\">Да</a> | <a href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]\">Нет</a><br />\n";
echo "</div>\n";
}

if (isset($_GET['act']) && $_GET['act']=='post_delete' && (isset($user) && $user['id']==$gruppy['admid'] || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1))
{
echo "<input value=\"Удалить выбранные посты\" type=\"submit\" /><br />\n";
echo "&laquo;<a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]'>Отмена</a><br />\n";
echo "</form>\n";
}

if ((!isset($_GET['act']) || $_GET['act']!='post_delete') && (isset($user) && $user['id']==$gruppy['admid'] || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1)){
echo "<div class=\"foot\">\n";
echo "&raquo; <a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=post_delete'>Удаление постов</a><br />\n";
echo "&raquo; <a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=mesto'>Переместить тему</a><br />\n";
echo "&raquo; <a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=set'>Параметры темы</a><br />\n";
if(isset($user) && $user['id']==$gruppy['admid']){
echo "&raquo; <a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=del'>Удалить тему</a><br />\n";}
echo "</div>\n";
}

?>
