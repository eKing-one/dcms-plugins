<?

if (isset($_GET['act']) && $_GET['act']=='set')
{
echo "<form method=\"post\" action=\"?s=$gruppy[id]&id_forum=$forum[id]&act=set&amp;ok\">\n";
echo "Название раздела:<br />\n";
echo "<input name='name' type='text' maxlength='32' value='$forum[name]' /><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit1\" value=\"1\" /> Транслит</label><br />\n";
echo "<input value=\"Изменить\" type=\"submit\" /><br />\n";
echo "&laquo;<a href='?s=$gruppy[id]&id_forum=$forum[id]'>Отмена</a><br />\n";
echo "</form>\n";
}

if (isset($_GET['act']) && $_GET['act']=='del')
{
echo "<div class=\"err\">\n";
echo "Подтвердите удаление раздела<br />\n";
echo "[<a href=\"?s=$gruppy[id]&id_forum=$forum[id]&act=delete&amp;ok\">Да, удалить.</a>] | ]<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\">Нет, отмена.</a>]<br />\n";
echo "</div>\n";
}


echo "<div class=\"mess\">\n";
echo "<u><a href='?s=$gruppy[id]&id_forum=$forum[id]&act=del'>Удалить форум</a></u><br />\n";
echo "<u><a href='?s=$gruppy[id]&id_forum=$forum[id]&act=set'>Параметры форума</a></u><br />\n";
echo "</div>\n";


?>