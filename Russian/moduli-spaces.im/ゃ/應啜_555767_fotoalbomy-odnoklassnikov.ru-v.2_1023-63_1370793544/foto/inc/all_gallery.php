<?
$set['title']='Фотоальбомы'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();

err();
aut();




$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет фотоальбомов\n";
echo "  </td>\n";
echo "   </tr>\n";

}

$q=mysql_query("SELECT * FROM `gallery` WHERE `my` = '0' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
$ank=get_user($post['id_user']);
echo "   <tr>\n";


echo "  <td class='p_t'>\n";
echo "<a href='/foto/$ank[id]/$post[id]/'>$post[name]</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_gallery` = '$post[id]'"),0)." фото)\n";


echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";

if ($post['opis']==null)
echo "Без описания<br />\n";
else 
echo esc(trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($post['opis']))))))))."<br />\n";
echo "Создан: ".vremja($post['time_create'])."<br />\n";
echo "Автор: <a href='/info.php?id=$ank[id]'>$ank[nick]</a><br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";




if ($k_page>1)str('?',$k_page,$page); // Вывод страниц



if (isset($user))
{
echo "<div class=\"foot\">\n";
echo "&raquo;<a href='/foto/$user[id]/'>Мои альбомы</a><br />\n";
echo "</div>\n";
}

include_once '../sys/inc/tfoot.php';
exit;
?>