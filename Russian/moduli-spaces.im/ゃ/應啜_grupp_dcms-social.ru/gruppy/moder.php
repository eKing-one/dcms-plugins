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
if(isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"),0)==1)
{
$s=intval($_GET['s']);
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$s' LIMIT 1"));
include_once 'inc/ban.php';
if(isset($_GET['invite']) && isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($_GET['invite']) && isset($user) && $user['id']==$gruppy['admid'])
{
$set['title']=$gruppy['name'].' - Пригласить людей в соо'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if(isset($_POST['uz']) && $_POST['uz']!=NULL && $_POST['uz']!=$user['id'] && $_POST['uz']!=$user['nick'])
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_POST['uz'])."' OR `nick`='".htmlspecialchars($_POST['uz'])."' LIMIT 1"),0)==1)
{
$uz=mysql_fetch_array(mysql_query("SELECT `id` FROM `user` WHERE `nick`='".htmlspecialchars($_POST['uz'])."' OR `id` = '".intval($_POST['uz'])."'"));
$uzer=get_user($uz['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '$uzer[id]' AND `id_gruppy`='$gruppy[id]' AND `activate`='1' LIMIT 1"),0)!=0){echo'<div class="err">Пользователь '.$uzer['nick'].' (ID: '.$uzer['id'].') уже подал заявку на вступление</div>';}
elseif(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '$uzer[id]' AND `id_gruppy`='$gruppy[id]' AND `invit`='1' LIMIT 1"),0)!=0){echo'<div class="err">Пользователю '.$uzer['nick'].' (ID: '.$uzer['id'].') уже высылалось приглашение</div>';}
elseif(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '$uzer[id]' AND `id_gruppy`='$gruppy[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)!=0){echo'<div class="err">Пользователь '.$uzer['nick'].' (ID: '.$uzer['id'].') уже состоит в сообществе</div>';}
elseif(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_bl` WHERE `id_user` = '$uzer[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1"),0)!=0){echo'<div class="err">Пользователь '.$uzer['nick'].' (ID: '.$uzer['id'].') находится в черном списке группы и Вы не можете его пригласить</div>';}
else{mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$uzer[id]', 'Пользователь $user[nick] прилашает Вас вступить в сообщество [url=/gruppy/$gruppy[id]]$gruppy[name][/url]. [url=/gruppy/index.php?s=$gruppy[id]&yes]Вступить[/url]/[url=/gruppy/index.php?s=$gruppy[id]&no]Отказаться[/url]', '$time')");
mysql_query("INSERT INTO `gruppy_users` (`id_gruppy`, `id_user`, `invit`, `time`, `invit_us`) values ('$gruppy[id]', '$uzer[id]', '1', '$time', '$user[id]')");
msg('Приглашение успешно отправлено');}
}
else
{
echo'<div class="err">Пользователь с ID или ником '.htmlspecialchars($_POST['uz']).' не найден</div>';
}
}

echo'Введите ник или ID юзера<br/>';
echo'<form method="post" action="?s='.$gruppy['id'].'&invite">';
echo'<input type="text" name="uz"><br/>';
echo'<input type="submit" value="Пригласить"><br/>';
echo "<div class='navi'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a><br/>';
echo "</div>\n";
}
else
{
$set['title']=$gruppy['name'].' - Администрация Группы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid']  || isset($user) && $user['level']>0)
{
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0' AND `mod`='0'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$adm=get_user($gruppy['admid']);
echo'<div class="main_menu">';
echo'<b>Администратор группы:</b>';
echo' '.online($adm['id']).' <a href="info.php?s='.$gruppy['id'].'&id='.$adm['id'].'"><span style="color:'.$adm['ncolor'].'">'.$adm['nick'].'</span></a>';
echo'</div>';
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `mod`='1' LIMIT 1"),0)>0)
$q2=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `mod`='1' ORDER BY `time` DESC LIMIT 3");
while ($moders = mysql_fetch_assoc($q2))
{
$ank_m=get_user($moders['id_user']);
echo '<tr>';
if($num==1){
echo "<div class='nav1'>\n";
$num=0;
}else{
echo "<div class='nav2'>\n";
$num=1;}
echo'<b>Модератор группы:</b>';
echo' '.online($ank_m['id']).' <a href="info.php?s='.$gruppy['id'].'&id='.$ank_m['id'].'"><span style="color:'.$ank_m['ncolor'].'">'.$ank_m['nick'].'</span></a>';
echo '</div>';
echo '</div>';
echo '</tr>';
}
//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="msg">';
echo 'Нет модераторов в данной группе =(';
echo '</div>';
echo '</tr>';
}

echo '</table>';
if ($k_page>1)str("?s=$gruppy[id]&",$k_page,$page); // Вывод страниц
echo "<div class='navi'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a><br/>';
echo "</div>\n";
}
else
{
echo'Вам недоступен просмотр участников данного сообщества';
}
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
