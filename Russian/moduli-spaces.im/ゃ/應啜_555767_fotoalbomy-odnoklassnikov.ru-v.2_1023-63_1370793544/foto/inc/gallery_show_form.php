<?



if ((user_access('foto_alb_del') || isset($user) && $user['id']==$ank['id']) && isset($_GET['act']) && $_GET['act']=='delete')
{
echo "<form class='foot' action='?act=delete&amp;ok&amp;page=$page' method=\"post\">";
echo "<div class='err'>Подтвердите удаление фотоальбома</div>\n";
echo "<center><input class=\"submit\" type=\"submit\" value=\"Удалить\" /> \n";
echo "<a href='?'>Отмена</a></center><br />\n";
echo "</form>";
include_once '../sys/inc/tfoot.php';
exit;
}


if (isset($user) && $user['id']==$ank['id'] && isset($_GET['act']) && $_GET['act']=='upload')
{
echo "<form class='foot' enctype=\"multipart/form-data\" action='?act=upload&amp;ok&amp;page=$page' method=\"post\">";
echo "Название:<br />\n";
echo "<input name='name' type='text' /><br />\n";
echo "Файл:<br />\n";
echo "<input name='file' type='file' accept='image/*,image/jpeg' /><br />\n";
echo "Описание:<br />\n";
echo "<textarea name='opis'></textarea><br />\n";

echo "<b>Размещаемые на Сайте Фото не должны:</b><br />\n";
echo "* нарушать действующее законодательство, честь и достоинство, права и охраняемые законом интересы третьих лиц, способствовать разжиганию религиозной, расовой или межнациональной розни, содержать сцены насилия, либо бесчеловечного обращения с животными, и т.д.;<br />\n";
echo "* носить непристойный или оскорбительный характер;<br />\n";
echo "* содержать рекламу наркотических средств;<br />\n";
echo "* нарушать права несовершеннолетних лиц;<br />\n";
echo "* нарушать авторские и смежные права третьих лиц;<br />\n";
echo "* носить порнографический характер;<br />\n";
echo "* содержать коммерческую рекламу в любом виде.<br />\n";

echo "<input class=\"submit\" type=\"submit\" value=\"Выгрузить\" /><br />\n";
echo "</form>";
echo "  <div class='foot_page'>\n";
echo "<a href='?'>Назад</a></div>\n";
include_once '../sys/inc/tfoot.php';
exit;
}

if (isset($user) && $user['id']==$ank['id'] || user_access('foto_alb_del')){

if (isset($user) && $user['id']==$ank['id'])
echo "<img src='/style/icons/pht2.png' alt=''/> <a href='?act=upload'>Новое фото</a><br />\n";
if (user_access('foto_alb_del') && $gallery['my']!=1 || isset($user) && $user['id']==$ank['id'] && $gallery['my']!=1)
echo "<img src='/style/icons/crs2.png' alt=''/> <a href='?act=delete'>Удалить фотоальбом</a><br />\n";

}

?>