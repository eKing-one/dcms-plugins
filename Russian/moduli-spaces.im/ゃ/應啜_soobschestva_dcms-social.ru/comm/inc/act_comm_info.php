<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Информацыя'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
echo "<div class='p_t'>\n";
echo "<span style='color: #79358c'><b>".htmlspecialchars($comm['name'])."</b></span><br/>\n";
echo "</div>\n";
echo "<div class='p_t'>\n";
echo "<b>Создано:</b> ".vremja($comm['time'])."<br/>\n";
echo "<b>Категория:</b> ".htmlspecialchars($cat['name'])."".($ank['id']==$user['id'] && isset($user)?" <a href='?act=comm_cat&id=$comm[id]'><img src='/comm/img/edit.png'/></a>":NULL)."<br/>\n";
if($comm['id_user']!=0)
{
echo "<b>Создатель:</b> \n";
echo "<a href='/info.php?id=$ank[id]'>$user[nick]</a> ".online($ank['id']);
echo "<br/>";
echo "<a href='?act=comm_users&id=$comm[id]&sort=2'>Список руководства</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `access` != 'user'"),0).")<br/>";
}
echo "</div>";
if($comm['deviz']!=NULL)echo "<div class='p_t'><b>Девиз:</b> ".htmlspecialchars($comm['deviz'])."</div>\n";
if($comm['desc']!=NULL)echo "<div class='p_t'><b>Описание:</b> ".htmlspecialchars($comm['desc'])."</div>\n";
if($comm['interests']!=NULL)echo "<div class='p_t'><b>Интересы:</b> ".htmlspecialchars($comm['interests'])."</div>\n";
if($comm['rules']!=NULL)echo "<div class='p_t'><b>Правила:</b> ".htmlspecialchars($comm['rules'])."</div>\n";
echo "<div class='p_t'>\n";
echo "<b>Членство:</b> ".($comm['join_rule']==1?"Свободное":NULL)."".($comm['join_rule']==2?"Через модератора":NULL)."".($comm['join_rule']==3?"По приглашениям":NULL)."<br/>\n";
echo "<b>Читатели:</b> ".($comm['read_rule']==1?"Только участники":NULL)."".($comm['read_rule']==2?"Все желающие":NULL)."<br/>\n";
echo "<b>Писатели:</b> ".($comm['write_rule']==1?"Только участники":NULL)."".($comm['write_rule']==2?"Все желающие":NULL)."<br/>\n";
echo "</div>\n";
if($ank['id']==$user['id'] && isset($user))echo "<div class='p_t'><img src='/comm/img/edit.png'/> <a href='?act=comm_object&id=$comm[id]'>Изменить</a><br/></div>\n";
echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>";
}
else{header("Location:/comm");exit;}
?>