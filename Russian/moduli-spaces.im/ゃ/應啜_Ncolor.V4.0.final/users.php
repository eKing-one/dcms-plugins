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

$set['title']='Пользователи'; // заголовок страницы
include_once 'sys/inc/thead.php';
title();
aut();
$sort='id';

$por='DESC';

if (isset($_GET['ASC']))$por='ASC'; // прямой порядок
if (isset($_GET['DESC']))$por='DESC'; // обратный порядок


switch (@$_GET['sort']) {
	case 'balls':$sql_sort='`user`.`balls`';$sort='balls'; // баллы
 	break;
	case 'level':$sql_sort='`user_group`.`level`';$sort='level'; // уровень
 	break;
	case 'rating':$sql_sort='`user`.`rating`';$sort='rating'; // рейтинг
 	break;
	case 'pol':$sql_sort='`user`.`pol`';$sort='pol'; // пол
 	break;
 	default:$sql_sort='`user`.`id`';$sort='id'; // ID
 	break;
}








if (!isset($_GET['go']))
{

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `user`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];





echo "<div class='foot'>\n";
echo "<a href='?sort=balls&amp;DESC&amp;page=$page'>баллы</a> \n";
echo "<a href='?sort=level&amp;DESC&amp;page=$page'>статус</a> \n";
echo "<a href='?sort=rating&amp;DESC&amp;page=$page'>рейтинг</a> \n";
echo "<a href='?sort=id&amp;ASC&amp;page=$page'>id</a> \n";
echo "<a href='?sort=pol&amp;ASC&amp;page=$page'>пол</a> \n";
echo "<a href='?sort=id&amp;DESC&amp;page=$page'>новые</a> \n";
echo "</div>\n";


echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет результатов\n";
echo "  </td>\n";
echo "   </tr>\n";

}
$q=mysql_query("SELECT `user`.`id` FROM `user` LEFT JOIN `user_group` ON `user`.`group_access` = `user_group`.`id` ORDER BY $sql_sort $por LIMIT $start, $set[p_str]");
while ($ank = mysql_fetch_assoc($q))
{
$ank=get_user($ank['id']);
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

if ($ank['group_access']>1)echo "<span class='status'>$ank[group_name]</span><br />\n";

if ($sort=='rating')
echo "<span class=\"ank_n\">Рейтинг:</span> <span class=\"ank_d\">$ank[rating]</span><br />\n";
if ($sort=='balls')
echo "<span class=\"ank_n\">Рейтинг:</span> <span class=\"ank_d\">$ank[balls]</span><br />\n";

if ($sort=='pol')
echo "<span class=\"ank_n\">Пол:</span> <span class=\"ank_d\">".(($ank['pol']==1)?'Мужской':'Женский')."</span><br />\n";

if ($sort=='id')
echo "<span class=\"ank_n\">Регистрация:</span> <span class=\"ank_d\">".vremja($ank['date_reg'])."</span><br />\n";
echo "<span class=\"ank_n\">Посл. посещение:</span> <span class=\"ank_d\">".vremja($ank['date_last'])."</span><br />\n";


if (user_access('user_prof_edit') && $user['level']>$ank['level'])
{
echo "<a href='/adm_panel/user.php?id=$ank[id]'>Редактировать профиль</a><br />\n";
}

echo "  </td>\n";
echo "   </tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("users.php?sort=$sort&amp;$por&amp;",$k_page,$page); // Вывод страниц

}


$usearch=NULL;
if (isset($_SESSION['usearch']))$usearch=$_SESSION['usearch'];
if (isset($_POST['usearch']))$usearch=$_POST['usearch'];

if ($usearch==NULL)
unset($_SESSION['usearch']);
else
$_SESSION['usearch']=$usearch;
$usearch=ereg_replace("( ){1,}","",$usearch);


if (isset($_GET['go']) && $usearch!=NULL)
{
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `nick` like '%".mysql_escape_string($usearch)."%' OR `id` = '".intval($usearch)."'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
echo "<div class='foot'>\n";
//echo "<a href=\"?sort=time&amp;page=$page\">время</a> | \n";
echo "<a href=\"?go&amp;sort=balls&amp;DESC&amp;page=$page\">баллы</a> \n";
echo "<a href=\"?go&amp;sort=level&amp;DESC&amp;page=$page\">статус</a> \n";
echo "<a href=\"?go&amp;sort=rating&amp;DESC&amp;page=$page\">рейтинг</a> \n";
echo "<a href=\"?go&amp;sort=id&amp;ASC&amp;page=$page\">id</a> \n";
echo "<a href=\"?go&amp;sort=pol&amp;ASC&amp;page=$page\">пол</a> \n";
echo "<a href=\"?go&amp;sort=id&amp;DESC&amp;page=$page\">новые</a> \n";
//echo "<a href=\"?sort=pereh&amp;page=$page\">переходы</a> | ";
//echo "<a href=\"?sort=time_all&amp;page=$page\">время на сайте</a>";
echo "</div>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет результатов\n";
echo "  </td>\n";
echo "   </tr>\n";

}
$q=mysql_query("SELECT `id` FROM `user` WHERE `nick` like '%".mysql_escape_string($usearch)."%' OR `id` = '".intval($usearch)."' ORDER BY `$sort` $por LIMIT $start, $set[p_str]");
while ($ank = mysql_fetch_assoc($q))
{
$ank=get_user($ank['id']);
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

if ($ank['level']!=0)echo "<span class=\"status\">$ank[group_name]</span><br />\n";

if ($sort=='rating')
echo "<span class=\"ank_n\">Рейтинг:</span> <span class=\"ank_d\">$ank[rating]</span><br />\n";
if ($sort=='balls')
echo "<span class=\"ank_n\">Рейтинг:</span> <span class=\"ank_d\">$ank[balls]</span><br />\n";

if ($sort=='pol')
echo "<span class=\"ank_n\">Пол:</span> <span class=\"ank_d\">".(($ank['pol']==1)?'Мужской':'Женский')."</span><br />\n";

if ($sort=='id')
echo "<span class=\"ank_n\">Регистрация:</span> <span class=\"ank_d\">".vremja($ank['date_reg'])."</span><br />\n";
echo "<span class=\"ank_n\">Посл. посещение:</span> <span class=\"ank_d\">".vremja($ank['date_last'])."</span><br />\n";

if (user_access('user_prof_edit') && $user['level']>$ank['level'])
{
echo "<a href='/adm_panel/user.php?id=$ank[id]'>Редактировать профиль</a><br />\n";
}


echo "  </td>\n";
echo "   </tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("users.php?go&amp;sort=$sort&amp;$por&amp;",$k_page,$page); // Вывод страниц
}
else
echo "<div class=\"post\">\nВведите ID или НИК юзера</div>\n";




echo "<form method=\"post\" action=\"/users.php?go&amp;sort=$sort&amp;$por\">";
echo "<input type=\"text\" name=\"usearch\" maxlength=\"16\" value=\"$usearch\" /><br />\n";
echo "<input type=\"submit\" value=\"Найти юзера\" />";
echo "</form>\n";

include_once 'sys/inc/tfoot.php';
?>