<?

if (isset($_GET['act']) && $_GET['act']=='frend')
{
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '$ank[id]' AND `i` = '1'"), 0);
$q = mysql_query("SELECT * FROM `frends` WHERE `user` = '$ank[id]' AND `i` = '1' ORDER BY time DESC");
if (isset($user) && $user['id']==$ank['id'])
{
if ($k_post>0)
echo "<form method='post' action='?'>";
}
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "У вас нет друзей\n";
echo "  </td>\n";
echo "   </tr>\n";
}

while ($frend = mysql_fetch_assoc($q))
{
$frend=get_user($frend['frend']);
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
if (isset($user) && $user['id']==$ank['id'])echo "<input type='checkbox' name='post_$frend[id]' value='1' /> ";
echo " <a href='/info.php?id=$frend[id]'>$frend[nick]</a> \n";
echo " ".online($frend['id'])." ";
echo "  </td>\n";
echo "   </tr>\n";
}

echo "</table>\n";
if (isset($user) && $user['id']==$ank['id'])
{
if ($k_post>0)
{
echo "<div class='nav1'>";
echo " Отмеченных друзей:<br />";
echo "<input value=\"Отметить\" type=\"submit\" name=\"metka\" /> <input value=\"Снять метку\" type=\"submit\" name=\"delete\" /> ";
echo "</div>";
echo "</form>\n";
}
}
include_once '../sys/inc/tfoot.php';
exit;
}

if (isset($_GET['act']) && $_GET['act']=='rename')
{
echo '<center>';
echo "<form class='foot' action='?act=rename&amp;ok' method=\"post\">";
echo "Название:<br />";
echo "<input name='name' type='text' value='$foto[name]' /><br />";
echo "Описание:<br />";
echo "<textarea name='opis'>".esc(stripcslashes(htmlspecialchars($foto['opis'])))."</textarea><br />";
echo "<input class='submit' type='submit' value='Применить' /> ";
echo " <a href='?'>Отмена</a><br />";
echo "</form>";
echo '</center>';
include_once '../sys/inc/tfoot.php';
exit;
}


if (isset($_GET['act']) && $_GET['act']=='delete')
{
echo '<center>';
echo "<form class='foot' action='?act=delete&amp;ok' method=\"post\">";
echo "<div class='err'>Подтвердите удаление фотографии</div>";
echo "<input class='submit' type='submit' value='Удалить' /> ";
echo " <a href='?'>Отмена</a><br />";
echo "</form>";
echo '</center>';
include_once '../sys/inc/tfoot.php';
exit;
}


echo "<div class=\"menu\">";
if ($gallery['my']=='1'){
echo "<img src='/style/icons/pht2.png' alt=''/> <a href='?act=ava&ok'>Сделать главной</a><br />";
}
echo "<img src='/style/icons/frd.png' alt=''/> <a href='?act=frend'>Отметить друзей</a><br />";
echo "<img src='/style/icons/pen2.png' alt=''/> <a href='?act=rename'>Изменить описание</a><br />";



echo "<img src='/style/icons/edt.png' alt=''/> <a href='/foto/create.php?foto=$foto[id]&amp;get'>Редактор</a><br />";

echo "<img src='/style/icons/crs2.png' alt=''/> <a href='?act=delete'>Удалить</a><br />";

echo "</div>";

?>