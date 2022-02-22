<?
/*
Модуль: Мои гости
Версия: 1
Автор: Merin
Аська: 7950048
*/

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
$set['title']='Мои гости';
include_once 'sys/inc/thead.php';
title();
aut();

switch (@$_GET['sort']) {
	case '1':$sql_sort="AND `time` > '".(time()-60*60*24)."'";$sort=1;$v='За сутки'; // за сутки
 	break;
	case '2':$sql_sort="AND `time` > '".(time()-60*60*168)."'";$sort=2;$v='За неделю'; // за неделю
 	break;
	case '3':$sql_sort="AND `time` > '".(time()-60*60*744)."'";$sort=3;$v='За месяц'; // за месяц
    break;
    case '4':$sql_sort="";$sort=4;$v='За все время'; // за месяц
    break;
    default:$sql_sort="AND `time` > '".(time()-60*60*24)."'";$sort=1;$v='За сутки';  // за сутки (стандартная)
 	break;
}
echo "<div class='foot'>\n";
echo "Вывести: ";
if ($sort==1)echo "[ за сутки | \n";
else echo "[ <a href='?sort=1'>за сутки</a> | \n";
if ($sort==2)echo "за неделю | \n";
else echo "<a href='?sort=2'>за неделю</a> | \n";
if ($sort==3)echo "за месяц | \n";
else echo "<a href='?sort=3'>за месяц</a> | \n";
if ($sort==4)echo "всех ]\n";
else echo "<a href='?sort=4'>всех</a> \n";
echo "</div>\n";
echo "<table class='post'>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `my_guest` WHERE `id_user` = '$user[id]' ".$sql_sort.""),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "".$v." вашу страницу не кто посещали!\n";
echo "  </td>\n";
echo "   </tr>\n";

}
$q=mysql_query("SELECT * FROM `my_guest` WHERE `id_user` = '".$user['id']."' ".$sql_sort." ORDER BY time DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
if ($post['newid']!=0)
{
$ank['id']=0;
$ank['pol']=1;
}
else
$ank=get_user($post['uid']);

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
if ($ank['id']==0)
echo "Невидимка\n";
else
echo "".online($ank['id'])." <a href='/info.php?id=".$ank['id']."'>".$ank['nick']."</a>\n";
if ($post['new']!=0) echo "<span style=\"color:#FF0000\">";
else "<span style=\"color:\">";
echo "(".vremja($post['time']).")\n";
echo "</span>";
echo "  </td>\n";
echo "   </tr>\n";

mysql_query("UPDATE `my_guest` SET `new` = '0' WHERE `id_user` = '".$user['id']."'");  // обнуление
}
echo "</table>\n";
if ($k_page>1)str("mail.php?id=$ank[id]&amp;",$k_page,$page); // Вывод страниц


include_once 'sys/inc/tfoot.php';
?>