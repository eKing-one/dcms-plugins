<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$searched=&$_SESSION['searched'];
if (!isset($searched) || isset($_GET['newsearch']) || isset($_GET['null']))
{
// зануляем весь запрос

$searched['in']=array('m'=>null);
$searched['text']=null;
$searched['query']=null;
$searched['sql_query']=null;
$searched['result']=array();
$searched['mark']=array();
}


if (isset($_GET['newsearch']))include 'inc/search_act.php';

$set['title']='Форум - поиск';
include_once '../sys/inc/thead.php';
title();
err();

if (isset($_GET['newsearch']))
{
if (count($searched['result'])!=0)
msg('По запросу "'.htmlentities($searched['text'], ENT_QUOTES, 'UTF-8').'" найдено совпадений:'.count($searched['result']));
elseif(!isset($err))
msg('По запросу "'.htmlentities($searched['text'], ENT_QUOTES, 'UTF-8').'" ничего не найдено');
}

aut(); // форма авторизации
//echo output_text($searched['sql_query'])."<br />\n";
//print_r($s_arr_mark);
$res=$searched['result'];
if (count($res)!=0)
{
$k_post=count($res);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$end=min($set['p_str']*$page,$k_post);
echo "<table class='post'>\n";
for($i=$start;$i<$end;$i++)
{
$them=$res[$i];
echo "   <tr>\n";
if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
echo "<img src='/style/themes/$set[set_them]/forum/48/them_$them[up]$them[close].png' />";
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/forum/14/them_$them[up]$them[close].png' alt='' />";
echo "  </td>\n";
}


echo "  <td class='p_t'>\n";
echo "<a href='/forum/$them[id_forum]/$them[id_razdel]/$them[id]/'>$them[name]</a> <a href='/forum/$them[id_forum]/$them[id_razdel]/$them[id]/?page=end'>(".mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_them` = '$them[id]'"),0).")</a>\n";
echo "  </td>\n";
echo "   </tr>\n";


echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_them` = '$them[id]'"),0)==$them['k_post'])
{

$post1=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' ORDER BY `time` ASC LIMIT 1"));
$ank=get_user($post1['id_user']);

echo "Автор: <a href='/info.php?id=$ank[id]' title='Анкета \"$ank[nick]\"'>\n";

echo GradientText("$ank[nick]", "$ank[ncolor]", "$ank[ncolor2]");
echo "</a>\n";
echo "(".vremja($them['time_create']).")<br />\n";

$post2=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' ORDER BY `time` DESC LIMIT 1"));
$ank2=get_user($post2['id_user']);

echo "Посл.: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>\n";

echo GradientText("$ank2[nick]", "$ank2[ncolor]", "$ank2[ncolor2]");
echo "</a>\n";
echo "(".vremja($post2['time']).")<br />\n";

}
else
{

echo esc(br(bbcode(preg_replace($searched['mark'], "<span class='search_cit'>\\1</span>",htmlentities($them['msg'], ENT_QUOTES, 'UTF-8')))))."<br />\n";



echo "Всего совпадений: $them[k_post]<br />\n";

}
echo "  </td>\n";
echo "   </tr>\n";
}
echo "</table>\n";

if ($k_page>1)str('?',$k_page,$page); // Вывод страниц
}
else
{


include 'inc/search_form.php';
}







echo "<div class=\"foot\">\n";
if (count($searched['result'])!=0)echo "&raquo;<a href='?null=$passgen'>Новый поиск</a><br />\n";
echo "&laquo;<a href='index.php'>Форум</a><br />\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>