<?

if (isset($user) && $user['id']==$ank['id']){

if (isset($_GET['act']) && $_GET['act']=='create')
{
echo "<form class=\"foot\" action='?act=create&amp;ok&amp;page=$page' method=\"post\">";
echo "Название альбома:<br />\n";
echo "<input type='text' name='name' value='' /><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit1\" value=\"1\" /> Транслит</label><br />\n";
echo "Описание:<br />\n";
echo "<textarea name='opis'></textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit2\" value=\"1\" /> Транслит</label><br />\n";
echo "<input class='submit' type='submit' value='Создать' /> \n";
echo "<br />\n";
echo "</form>";
echo "  <div class='foot_page'>\n";
echo "<a href='?'>Назад</a></div>\n";
include_once '../sys/inc/tfoot.php';
exit;
}


echo "<img src='/style/icons/pls2.png' alt=''/> <a href='/foto/$ank[id]/?act=create'>Создать фотоальбом</a><br />\n";


}

?>