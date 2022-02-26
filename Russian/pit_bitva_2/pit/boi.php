<?php
# МОД МОЙ ПИТОМЕЦ
# KAZAMA
# 383991000

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Бои питомцев';
include_once '../sys/inc/thead.php';
title();
err();
aut();
include_once 'head.php';

if (isset($_GET['id']))$id_get=intval($_GET['id']);
$q_u=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='$id_get'"));
$q=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='$user[id]'"));


if (isset($user)&&isset($_GET['atak'])){
$ololo=round($q[sila]*3)+$q[zashita];
$ololom=round($q_u[sila]*3)+$q_u[zashita];
if($q['boi']!=1)echo "<div class=err>Вы не участвуете в боях!</div>";
elseif($q['timenap']>time())echo "<div class=err>Перезарядка $time_boii часа ещё не прошло!</div>";
elseif($ololo>$ololom && $q[zdorov]>$q_u[zdorov]){
$timenap=time()+60*60*$time_boii;
$msila=round($q_u['sila']/2);
$mzashita=round($q_u['zashita']/2);
msg('Вы выиграли! К вам присоединилось '.$msila.' силы, '.$mzashita.' зашиты и потеряли при битве 25 здаровье!');
mysql_query("UPDATE `pit` SET `lose`=`lose`+1, `boi`='0',`zdorov`=`zdorov`-50, `sila`=`sila`-$msila, `zashita`=`zashita`-$mzashita WHERE `id_user`='".$q_u[id_user]."' LIMIT 1");
mysql_query("UPDATE `pit` SET `win`=`win`+1,`timenap`='$timenap',`zdorov`=`zdorov`-25, `sila`=`sila`+$msila, `zashita`=`zashita`+$mzashita WHERE `id_user`='".$q[id_user]."' LIMIT 1");
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$id_get', 'Вы проиграле в [url=/pit]боях питомцев[/url]! У вас ушло $msila силы, $mzashita зашиты и потеряли при битве 50 здаровье!', '$time')");
}elseif($ololo<$ololom){
$timenap=time()+60*60*$time_boii;
$msila=round($q['sila']/2);
$mzashita=round($q['zashita']/2);
msg('Вы проиграле ! У вас ушло '.$msila.' силы, '.$mzashita.' зашиты и потеряли при битве 50 здаровье!');
mysql_query("UPDATE `pit` SET `lose`=`lose`+1, `boi`='0',`zdorov`=`zdorov`-50, `sila`=`sila`-$msila, `zashita`=`zashita`-$mzashita WHERE `id_user`='".$q[id_user]."' LIMIT 1");
mysql_query("UPDATE `pit` SET `win`=`win`+1,`timenap`='$timenap',`zdorov`=`zdorov`-25, `sila`=`sila`+$msila, `zashita`=`zashita`+$mzashita WHERE `id_user`='".$q_u[id_user]."' LIMIT 1");
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$id_get', 'Вы выиграли в [url=/pit]боях питомцев[/url]! К вам присоединилось $msila силы, $mzashita зашиты и потеряли при битве 25 здаровье!', '$time')");
}
}

if (isset($_GET['prin'])&& isset($user))
{
if($q['boi']==2)echo "<div class=err>Вам запрешено участовать!</div>";
elseif($q['sila']<30)echo "<div class=err>Сила должно быть больше 30!</div>";
elseif($q['zashita']<30)echo "<div class=err>Защита должно быть больше 30!</div>";
elseif($q['zdorov']<50)echo "<div class=err>Здоровье не должно быть меньше 50!</div>";
elseif($q['boi']==1)echo "<div class=err>Вы уже участвуете!</div>";
else{
mysql_query("UPDATE `pit` SET `boi` = '1' WHERE `id_user` = '".mysql_escape_string($user['id'])."' LIMIT 1");
msg ('Успешно добавились в арену');
}
}


if (isset($user)&&mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$user[id]' AND `boi` = '1'"), 0)==0)echo '<img src="icon/bitva/arena.png" alt="" class="icon"/> <a href="?prin">Принять участия</a>';

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `boi`='1' "), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo '<div class="menu">Нет питомцев!</div>';
$q = mysql_query("SELECT * FROM `pit` WHERE `boi`='1' ORDER BY `sila` DESC LIMIT $start, $set[p_str]");
while ($f = mysql_fetch_array($q))
{
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`='".$f['id_user']."'"));
echo '<tr><table class="post"><tr><td class="icon48" rowspan="2">';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$ank[id]'"),0) != '0')echo '<img src="img/'.$f[pit].'.png" alt="" class="icon"/>';
if (isset($user)&&$f[id_user]!=$user[id])echo '<div class="msg"><a href="?id='.$f[id_user].'&atak">Напасть</a></div>';else if (isset($user))echo '<div class="err">Это вы</div>';
echo '</td>';
echo '<td class="p_m">';
if ($f['name']!=NULL)echo "<img src='icon/pit.png'> <a href='index.php?id=$f[id_user]'>$f[name]</a> [<font color=lime>$f[win]</font>|<font color=red>$f[lose]</font>]<br />";else echo"<br /><img src='icon/pit.png'> <a href='index.php?id=$f[id_user]'>без имени</a><br/>";
echo "<img src='icon/bitva/str.png' alt='' class='icon'> Сила: $f[sila]<br/><img src='icon/bitva/vit.png' alt='' class='icon'> Здоровье: $f[zdorov]<br/><img src='icon/bitva/def.png' alt='' class='icon'> Защита: $f[zashita]<br /></a>\n";
echo "</td>";
echo "</tr>";
echo '</table>';

}
echo "<div class='foot'>";
if ($k_page>1)str("?",$k_page,$page); // Вывод страниц
echo "</div>";

echo '<div class="msg"><a href="index.php?">В игру</a></div><br/> Зоздатель игры <a href="http://vent.besaba.com">У нас весело</a>';
include_once '../sys/inc/tfoot.php';

?>