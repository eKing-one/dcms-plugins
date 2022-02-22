<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
only_reg();
$set['title']='Отзывы обо мне';
include_once 'sys/inc/thead.php';
title();
aut();



$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `user_voice2` WHERE `id_kont` = '$user[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет положительных отзывов\n";
echo "  </td>\n";
echo "   </tr>\n";

}

$q=mysql_query("SELECT * FROM `user_voice2` WHERE `id_kont` = '$user[id]' LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
$ank=get_user($post['id_user']);
echo "   <tr>\n";
if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
avatar($ank['id']);
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/user/$ank[pol].png' alt='' />";
echo "  </td>\n";
}
echo "  <td class='p_t'>\n";
echo "<a href='/info.php?id=$ank[id]'>\n";
echo GradientText("$ank[nick]", "$ank[ncolor]", "$ank[ncolor2]");
echo "</a>\n";
echo "".online($ank['id'])."\n";
echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
echo "Отзыв:<br />\n";
switch ($post['rating'])
{
case 2:echo "Замечательный<br />\n";	break;
case 1:echo "Положительный<br />\n";	break;
case 0:echo "Нейтральный<br />\n";	break;
case -1:echo "Не очень...<br />\n";	break;
case -2:echo "Негативный<br />\n";	break;
}
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";




if ($k_page>1)str('?',$k_page,$page); // Вывод страниц



//echo "* Негативные отзывы не отображаются во избежание конфликтов<br />\n";

echo "<div class='foot'>\n";
echo "&raquo;<a href='/info.php'>Моя анкета</a><br />\n";
echo "</div>\n";
include_once 'sys/inc/tfoot.php';
?>