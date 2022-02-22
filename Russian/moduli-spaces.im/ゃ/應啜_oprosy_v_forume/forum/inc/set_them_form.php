<?

if (isset($_GET['act']) && $_GET['act']=='mesto' && (user_access('forum_them_edit') || $ank2['id']==$user['id']))
{
echo "<form method=\"post\" action=\"/forum/$forum[id]/$razdel[id]/$them[id]/?act=mesto&amp;ok\">\n";
echo "Раздел:<br />\n";

echo "<select name=\"razdel\">\n";


if (user_access('forum_them_edit')){
$q = mysql_query("SELECT * FROM `forum_f` ORDER BY `pos` ASC");
while ($forums = mysql_fetch_assoc($q))
{
echo "<optgroup label='$forums[name]'>\n";
$q2 = mysql_query("SELECT * FROM `forum_r` WHERE `id_forum` = '$forums[id]' ORDER BY `time` DESC");
while ($razdels = mysql_fetch_assoc($q2))
{
echo "<option".($razdel['id']==$razdels['id']?' selected="selected"':null)." value=\"$razdels[id]\">$razdels[name]</option>\n";
}
echo "</optgroup>\n";
}
}
else
{

$q2 = mysql_query("SELECT * FROM `forum_r` WHERE `id_forum` = '$forum[id]' ORDER BY `time` DESC");
while ($razdels = mysql_fetch_assoc($q2))
{
echo "<option".($razdel['id']==$razdels['id']?' selected="selected"':null)." value='$razdels[id]'>$razdels[name]</option>\n";
}
}
echo "</select><br />\n";

echo "<input value=\"Переместить\" type=\"submit\" /><br />\n";
echo "&laquo;<a href='/forum/$forum[id]/$razdel[id]/$them[id]/'>Отмена</a><br />\n";
echo "</form>\n";
}

if (isset($_GET['act']) && $_GET['act']=='set' && ((user_access('forum_them_edit') && $ank2['level']<$user['level']) || $ank2['id']==$user['id']))
{
echo "<form method='post' action='/forum/$forum[id]/$razdel[id]/$them[id]/?act=set&amp;ok'>\n";

//valerik_mod Опрос в форуме-------------
echo '<a href="?act=add_opros">Опрос темы</a><br />';
//---------------------------------------

echo "Название темы:<br />\n";
echo "<input name='name' type='text' maxlength='32' value='$them[name]' /><br />\n";
if ($user['set_translit']==1)echo "<label><input type='checkbox' name='translit1' value='1' /> Транслит</label><br />\n";


if ($user['level']>0){
if ($them['up']==1)$check=' checked="checked"';else $check=NULL;
echo "<label><input type=\"checkbox\"$check name=\"up\" value=\"1\" /> Всегда наверху</label><br />\n";
}
if ($them['close']==1)$check=' checked="checked"';else $check=NULL;
echo "<label><input type=\"checkbox\"$check name=\"close\" value=\"1\" /> Закрыть</label><br />\n";


if ($ank2['id']!=$user['id']){
echo "<label><input type=\"checkbox\" name=\"autor\" value=\"1\" /> Забрать у автора права</label><br />\n";
}

echo "<input value=\"Изменить\" type=\"submit\" /><br />\n";
echo "&laquo;<a href='/forum/$forum[id]/$razdel[id]/$them[id]/'>Отмена</a><br />\n";
echo "</form>\n";
}

//valerik_mod Опросы на форуме-------
$mod_file=H.'mods/forum_opros/set_them_form.php';
if(is_file($mod_file))include_once $mod_file;
//-----------------------------------



if (isset($_GET['act']) && $_GET['act']=='del' && user_access('forum_them_del') && ($ank2['level']<$user['level'] || $ank2['id']==$user['id']))
{
echo "<div class=\"err\">\n";
echo "Подтвердите удаление темы<br />\n";
echo "<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?act=delete&amp;ok\">Да</a> <a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/\">Нет</a><br />\n";
echo "</div>\n";
}

if (isset($_GET['act']) && $_GET['act']=='post_delete' && (user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']))
{
echo "<input value=\"Удалить выбранные посты\" type=\"submit\" /><br />\n";
echo "&laquo;<a href='/forum/$forum[id]/$razdel[id]/$them[id]/'>Отмена</a><br />\n";
echo "</form>\n";
}

if (((!isset($_GET['act']) || $_GET['act']!='post_delete') && (user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']))
|| ((user_access('forum_them_edit') && $ank2['level']<$user['level']) || $ank2['id']==$user['id'])
|| (user_access('forum_them_del') && ($ank2['level']<$user['level'] || $ank2['id']==$user['id']))){
echo "<div class=\"foot\">\n";

if ((!isset($_GET['act']) || $_GET['act']!='post_delete') && (user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']))
echo "&raquo;<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?act=post_delete'>Удаление постов</a><br />\n";

if ((user_access('forum_them_edit') && $ank2['level']<$user['level']) || $ank2['id']==$user['id']){
echo "&raquo;<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?act=mesto'>Переместить тему</a><br />\n";
echo "&raquo;<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?act=set'>Параметры темы</a><br />\n";
}
if (user_access('forum_them_del') && ($ank2['level']<$user['level'] || $ank2['id']==$user['id'])){
echo "&raquo;<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?act=del'>Удалить тему</a><br />\n";
}
echo "</div>\n";
}

?>