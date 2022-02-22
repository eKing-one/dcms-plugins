Мод нижней навигацыи сайта

[//////100% рабочий!!!//////]

Описание:
Администацыя сайта сможет добавить ссылки:
Название,ее сокражене(жур,фор),ссылка и иконка.Список иконок для выбора выводится из папки style/icons.Когда админ добавил ссылку,то пользователь сможет выбрать ее для вывода снизу сайта(нижняя навигацыя).Пользователь сможет поменять ее располажение или же удалить ее.
Пользователь также сможет настроит располажение всех ссылок:
- слева | в центе | справа
- графично | текст
- включить/выключит показ нижней навигацыи

[+ добавлена возможность добавлять свои ссылки на любой ресус или обект!!!]

[///////ОЧЕНЬ НУЖНАЯ ШТУКА!///////]

Установка:
1.Разархивировать в корень сайта
2.Выполнить запросы из файла tables.sql
3.В файле settings.php прописать код:

echo "$raquo; <a href='/links/'>Настроить нижнюю навигацию</a><br/>\n";

4.В файле foot.php прописать код:

if($user['show_foot']=='on')
{
echo "<div style='text-align: $user[foot_sit]'>\n";
$links = mysql_query("SELECT * FROM `links_niz_user` WHERE `id_user` = '$user[id]' ORDER BY `pos` ASC");
while ($post = mysql_fetch_array($links))
{
$link=mysql_fetch_array(mysql_query("SELECT * FROM `links_niz` WHERE `id` = '$post[id_link]' LIMIT 1"));
if($post['link']=='0')
{
if($user['show_foot_type']=='icons')echo "<a href='$link[url]'><img src='/style/icons/$link[icon]' /></a> | \n";
else echo "<a href='$link[url]'>$link[sname]</a> | \n";
}
else
{
if($user['show_foot_type']=='icons')echo "<a href='$post[link]'><img src='/style/icons/$post[icon]' /></a> | \n";
else echo "<a href='$post[link]'>$post[link_name]</a> | \n";
}
}
echo "<a href='/links/'>+</a>\n";
echo "</div>\n";
}
