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
$set['title']='Лента'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
only_reg();
echo "<div id='content'>\n";







$on1 = mysql_result(mysql_query("select count(*) from `group_lenta`, `group_users` where `group_users`.`user`='".$user['id']."' and `group_lenta`.`group`=`group_users`.`group` and `group_users`.`time`<`group_lenta`.`time`;"),0);
$on10 = ($on1!=0) ? '+'.$on1.'' : '';



echo "<div class='title'>Группы $on10 </div>";




$k_post=mysql_result(mysql_query("select count(*) from `group_lenta`, `group_users` where `group_users`.`user`='".$user['id']."' and `group_lenta`.`group`=`group_users`.`group`;"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){
echo '<div class="err">Лента пуста!</div>';}
$q=mysql_query("select `group_users`.*, `group_lenta`.* from `group_users`, `group_lenta` where `group_users`.`user`='".$user['id']."' and `group_lenta`.`group`=`group_users`.`group`  order by `group_lenta`.`time` desc LIMIT " . $start . ", " . $set['p_str'] . "");
while ($data = mysql_fetch_assoc($q)){
$group = mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = '$data[group]' LIMIT 1"));


//echo '<div class="chdr">';

echo '<a  href="index.php?act=view&id='.$data['group'].'">'.$group['title'].'</a>';
echo '<div class="nav2">';
echo output_text($data['text']);
echo ' ('.times($data['time']).')';
echo '</div>';
echo "<div class='pdiv'></div>";
mysql_query("UPDATE `group_users` SET `time` = '".$time."' WHERE `user` = '".$user['id']."' and `group` = '$data[group]'");
}
echo'</ul>';
if ($k_page>1)str('lenta.php?act=index&',$k_page,$page); // Вывод страниц
echo'</div>';

echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Лента группы
case "group":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$set['title']='Лента | '.$data['title']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo'<div id="content">';




echo'<div class="nav1">Лента группы:';
echo'<a href="index.php?act=view&id='.$data['id'].'" class="grp">'.$data['title'].'</a></div>';
echo'<div class="pnl2B"></div>';


$k_post=mysql_result(mysql_query("select count(*) from `group_lenta` where `group`='".$id."';"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){echo '<div class="err">Лента пуста!</div>';}
$q=mysql_query("select * from `group_lenta` where `group`='".$id."' order by `time` desc LIMIT " . $start . ", " . $set['p_str'] . "");
while ($lenta = mysql_fetch_assoc($q)){






echo '<div class="nav2">';
echo output_text($lenta['text']);
echo '('.times($lenta['time']).')';
echo '</div';

echo "<div class='pdiv'></div>";


}

if ($k_page>1)str('lenta.php?act=group&id='.$id.'&',$k_page,$page); // Вывод страниц
echo'</div>';

$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = '$id' LIMIT 1"));

echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';


break;
default:
header("location: index.php?");
endswitch;


?>