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
include_once 'config.php';
if (isset($_GET['act'])) {$act = altec($_GET['act']);} else {$act = 'index';} 
switch ($act):
### Главная страница
case "index":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id`='$id'"),0)==0)header("Location: index.php");
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$id' LIMIT 1"));
$title = ($ank['id']==$user['id']) ? 'Мои группы' : 'Группы '.$ank['nick'];
$set['title'] = $title; // заголовок страницы
include_once '../sys/inc/thead.php';
title();





$new=mysql_result(mysql_query("select count(*) from `group_lenta`, `group_users` where `group_users`.`user`='".$user['id']."' and `group_lenta`.`group`=`group_users`.`group` and `group_users`.`time`<`group_lenta`.`time`;"),0);
$new = ($new!=0) ? ''.$new.'' : '';

$group1=mysql_result(mysql_query("SELECT COUNT(*) FROM `group`"),0);
$group2=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `user`='".$user['id']."'"),0);
echo'<div class="foot">';
echo ' <a  href="/group/group.php?id='.$user['id'].'"><span class="nav1">Мои &nbsp;'.$group2.'</span></a>';
echo ' <a  href="/group/index.php"><span class="nav1">Все &nbsp;'.$group1.'</span></a>';
echo ' <a  href="lenta.php"><span class="nav1">Лента &nbsp;'.$new.'</span></a>';
echo'</div>';




$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `user`='$id'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){echo '<div class="err">Вы не вступали в группу!!</div>';}
$q=mysql_query("SELECT * FROM `group_users` WHERE `user`='$id' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($data = mysql_fetch_assoc($q))

{




$gr=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = '$data[group]' LIMIT 1"));

if ($num == 0)
{echo "  <div class='nav1'>\n";
$num=1;
}elseif ($num == 1)
{echo "  <div class='nav2'>\n";
$num=0;}

$neww=mysql_result(mysql_query("select count(*) from `group_lenta`, `group_users` where `group_users`.`user`='".$user['id']."' and `group_lenta`.`group`=`group_users`.`group`and `group_lenta`.`group`='".$gr['id']."' and `group_users`.`time`<`group_lenta`.`time`;"),0);
$new1 = ($neww!=0) ? ''.$neww.'' : '';
echo'<a href="index.php?act=view&id='.$gr['id'].'" class="clkbc">';
echo group_img($gr['id']);
echo ''.$gr['title'].'';
echo " &nbsp;$new1";
echo'</div>';
$lenta_userr=mysql_result(mysql_query("select count(*) from `group_users` where `user`='".$user['id'].""),0);
echo'</a>';
}
if ($k_page>1)str('group.php?id='.$id.'&amp;',$k_page,$page); // Вывод страниц
break;
default:
header("location: index.php?");
endswitch;
include_once '../sys/inc/tfoot.php';
