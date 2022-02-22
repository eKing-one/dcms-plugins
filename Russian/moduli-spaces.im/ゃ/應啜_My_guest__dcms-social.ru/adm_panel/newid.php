<?
/*
Модуль: Мои гости
Версия: 1
Автор: Merin
Аська: 7950048
*/

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/adm_check.php';
include_once '../sys/inc/user.php';
user_access('adm_mysql',null,'index.php?'.SID);
adm_check();
$set['title']='Кто активировал'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();
err();
aut();

echo "<table class='post'>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `newid`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Услугу еще не кто не активировал!<br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
$q=mysql_query("SELECT * FROM `newid` ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)) {

$ank=get_user($post['uid']);
echo "   <tr>\n";

echo " <td class='foot'>\n";
echo "Пользователь: \n";
echo "".online($ank['id'])." <a href='/info.php?id=".$ank['id']."'>".$ank['nick']."</span></a><br />\n";
echo "Услуга активна до: ".vremja($post['time'])."<br />\n";
echo "   </tr>\n";
echo "   </td>\n";
}
echo "</table>\n";

if ($k_page>1)str("sms_pod.php?",$k_page,$page);

if (user_access('adm_panel_show')){
echo "<div class='foot'>\n";
echo "<a href='/adm_panel/'>В админку</a><br />\n";
echo "</div>\n";
}
include_once '../sys/inc/tfoot.php';
?>
