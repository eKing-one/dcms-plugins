<?php
# МОД МОЙ ПИТОМЕЦ
# KAZAMA
# 383991000
error_reporting(0);
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Мой питомец';
include_once '../sys/inc/thead.php';
title();
aut();


if (!isset($user) && !isset($_GET['id'])){header("Location: index.php?".SID);exit;}
if (isset($user))$q_ank['id']=$user['id'];
if (isset($_GET['id']))$q_ank['id']=intval($_GET['id']);

$q_user=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='".$user['id']."'"));
$q_ank=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='".$q_ank['id']."'"));

if (isset($user)&&$q_ank['id_user']!=$user['id'])include_once 'head.php';

if (isset($_GET['zapret'])&& isset($user)&& $user['level']>=3){
if ($_GET['zapret']==2)
mysql_query("UPDATE `pit` SET `boi` = '2' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
if ($_GET['zapret']==0)
mysql_query("UPDATE `pit` SET `boi` = '0' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
msg ('Успешно выполнено');
}

if (isset($_GET['delete'])&& isset($user)&& $user[level]>=3){
if (isset($user)&& mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$q_ank[id_user]'"),0) == '0')echo "<div class=err>Такого пользователя нету</div>";
else{
mysql_query("DELETE FROM `pit` WHERE `id_user` = '".mysql_escape_string($q_ank[id_user])."'");
msg ('Пользователь успешно удалён');
}
}



if (isset($_GET['edit'])&& isset($user)&& $user[level]>=3){
if (isset($_GET['set']) && isset($_POST['ok']))
{
$name=mysql_real_escape_string(esc(stripcslashes(htmlspecialchars($_POST['name']))));
$sila=mysql_real_escape_string(esc(stripcslashes(htmlspecialchars($_POST['sila']))));
if (strlen2($name)<3)$err='Короткое имя!';
if (strlen2($name)>1500)$err='В имени не должно быть длиннее 150-х символов!';
if (!isset($err))
{
mysql_query("UPDATE `pit` SET `name` = '$name' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
mysql_query("UPDATE `pit` SET `sila` = '$sila' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
mysql_query("UPDATE `pit` SET `zdorov` = '".mysql_real_escape_string($_POST['zdorov'])."' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
mysql_query("UPDATE `pit` SET `zashita` = '".mysql_real_escape_string($_POST['zashita'])."' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
mysql_query("UPDATE `pit` SET `pit` = '".mysql_real_escape_string($_POST['pit_id'])."' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
mysql_query("UPDATE `pit` SET `igra` = '".mysql_real_escape_string($_POST['igra_id'])."' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
mysql_query("UPDATE `pit` SET `dom` = '".mysql_real_escape_string($_POST['dom_id'])."' WHERE `id_user` = '$q_ank[id_user]' LIMIT 1");
header("Location: ?id=$q_ank[id_user]");
}
}
echo "<div class='menu'>\n";
echo "<form method='post' action='?id=$q_ank[id_user]&edit&set'><div>\n";
echo "Имя :<br /><input type='text' name='name' value='$q_ank[name]' maxlength='1500' /><br />";
echo "Сила :<br /><input type='text' name='sila' value='$q_ank[sila]' maxlength='6' /><br />";
echo "Здоровье :<br /><input type='text' name='zdorov' value='$q_ank[zdorov]' maxlength='6' /><br />";
echo "Защита :<br /><input type='text' name='zashita' value='$q_ank[zashita]' maxlength='6' /><br />";
echo "<div class='err'>Если вы не разбираетесь, лучьше чё внизу не трогать! </div>Питомец ID:<br /><input type='text' name='pit_id' value='$q_ank[pit]' maxlength='6' /><br />";
echo "Игрушка ID:<br /><input type='text' name='igra_id' value='$q_ank[igra]' maxlength='6' /><br />";
echo "Дом ID:<br /><input type='text' name='dom_id' value='$q_ank[dom]' maxlength='6' /><br />";
echo "<input class='doctor' type='submit' name='ok' value='Изменить' />\n";
echo "</div>\n";
echo "</form>\n";
echo "</div>";
echo '<div class="msg"><a href="?">В игру</a></div>';
include_once '../sys/inc/tfoot.php';
exit;
}


if (isset($_GET['setting'])){
include_once 'head.php';

echo "<div class='p_m'>";
if (isset($user)&&$q_ank['id_user']==$user[id])echo'<img src="icon/bitva/str.png" alt="" class="icon"/><a href="sila.php">Купить силу</a><br />';
if (isset($user)&&$q_ank['id_user']==$user[id])echo'<img src="icon/bitva/vit.png" alt="" class="icon"/><a href="zdorov.php">Купить здоровье</a><br />';
if (isset($user)&&$q_ank['id_user']==$user[id])echo'<img src="icon/bitva/def.png" alt="" class="icon"/><a href="zashita.php">Купить защиту</a><br />';
if (isset($user)&&$q_user['dom']== 0&&$q_ank['id_user']==$user[id])echo'<img src="icon/pit.png" alt="" class="icon"/><a href="dom.php">Купить дом</a><br />';elseif (isset($user)&&$q_ank['id_user']==$user['id']) echo'<img src="icon/pit.png" alt="" class="icon"/><a href="dom.php">Изменить дом</a><br />';
if (isset($user)&&mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$user[id]'"),0) == '0')echo'<img src="icon/pit.png" alt="" class="icon"/> ><a href="pit.php">Купить питомца</a><br />';elseif (isset($user)&&$q_ank['id_user']==$user['id']&&mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$q_ank[id_user]'"),0) != '0') echo'<img src="icon/pit.png" alt="" class="icon"/><a href="pit.php">Изменить питомца</a><br />';
if (isset($user)&&$q_ank['igra']==0&&$q_ank['id_user']==$user[id])echo'<img src="icon/pit.png" alt="" class="icon"/><a href="igra.php">Купить игрушку</a><br />';elseif (isset($user)&&$q_ank['id_user']==$user['id']) echo'<img src="icon/pit.png" alt="" class="icon"/><a href="igra.php">Изменить игрушку</a><br />';
echo "</div>";
echo '<div class="msg"><a href="?">В игру</a></div>';
include_once '../sys/inc/tfoot.php';
exit;
}



if ($q_ank['dom']!=0)echo '<img src="img/dom/'.$q_ank[dom].'.png" alt="" class="icon"/>';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$q_ank[id_user]'"),0) == '0')echo'<div class="p_m">Нет питомца<br /><img src="icon/pit.png" alt="" class="icon"/><a href="pit.php">Купить питомца</a><br/>';else {echo '<img src="img/'.$q_ank[pit].'.png" alt="" class="icon"/>';
if ($q_ank['igra']!=0)echo '<img src="img/igra/'.$q_ank[igra].'.png" alt="" class="icon"/>';
echo'<br /><div class="p_m"><img src="icon/bitva/1.png" alt="" class="icon"> Имя: '.$q_ank[name].'<br /><img src="icon/bitva/str.png" alt="" class="icon"> Сила: '.$q_ank[sila].'<br /><img src="icon/bitva/vit.png" alt="" class="icon"> Здоровье: '.$q_ank[zdorov].'<br/><img src="icon/bitva/def.png" alt="" class="icon"> Защита: '.$q_ank[zashita].'<br/><img src="icon/bitva/quest.png" alt="" class="icon"> Побед: [<font color=lime>'.$q_ank[win].'</font>|<font color=red>'.$q_ank[lose].'</font>]</br /><img src="icon/bitva/0.png" alt="" class="icon">  Дата рождения: '.vremja($q_ank[time]).'</div>';
}
echo '</div><div class="foot">';

if (isset($user)&& $user[level]>=3)echo "<img src='icon/set.gif' alt='!'> <a href='?id=$q_ank[id_user]&edit'><font color=red>Редактировать</font></a><br/>\n";
if (isset($user)&& $user[level]>=3&&$q_ank[boi]!=2&&$q_ank['id_user']!=$user[id])echo "<img src='icon/set.gif' alt='!'> <a href='?id=$q_ank[id_user]&zapret=2'><font color=red>Запретить драться</font></a><br/>\n";
if (isset($user)&& $user[level]>=3&&$q_ank[boi]==2&&$q_ank['id_user']!=$user[id])echo "<img src='icon/set.gif' alt='!'> <a href='?id=$q_ank[id_user]&zapret=0'><font color=red>Разрешить драться</font></a><br/>\n";
if (isset($user)&& $user[level]>=3&&$q_ank['id_user']!=$user[id])echo "<img src='icon/set.gif' alt='!'> <a href='?id=$q_ank[id_user]&delete'><font color=red>Удалить питомца</font></a><br/>\n";
if (isset($user)&&$q_ank['id_user']==$user[id])echo '<img src="icon/set.gif" alt="" class="icon"/> <a href="?setting">Настойки</a><br/>';
$cound_pit= mysql_result(mysql_query("SELECT COUNT(*) FROM `pit`",$db), 0);
echo '<img src="icon/top.png" alt="" class="icon"/> <a href="vse.php">Все питомцы</a> ('.$cound_pit.')<br/>';
$cound_boi= mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `boi`='1'",$db), 0);
echo '<img src="icon/bitva/arena.png" alt="" class="icon"/> <a href="boi.php">Битва питомцев</a> ('.$cound_boi.')<br/>';

echo "</div>";
echo '<div class="msg"><a href="index.php?">В игру</a></div><br/> Зоздатель игры <a href="http://vent.besaba.com">У нас весело</a>';
include_once '../sys/inc/tfoot.php';

?>