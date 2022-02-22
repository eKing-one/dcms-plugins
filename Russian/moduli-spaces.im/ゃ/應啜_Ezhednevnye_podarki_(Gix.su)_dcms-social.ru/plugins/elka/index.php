<?php

/*
Автор: Optimuses
Мод: Ежедневные подарки
Цена: 50wmr
*/

include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';

only_reg();
$set['title'] = 'Ежедневные подарки';
include_once '../../sys/inc/thead.php';
title();
aut();

echo "<div class='mess'>В этом разделе Вы можете каждый день получать по 1 подарку к Новому Году.</div>";

if(isset($_GET['gift']) && $user['time_gift']<=$time){
$rand = rand(1,9);
if($rand==1){
mysql_query("UPDATE `user` SET `balls` = `balls` + '300' WHERE `id`='$user[id]' ");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." +300 баллов', '$time')");
msg('Поздравляем! Вы получили +300 баллов.');
} elseif($rand==2) {
mysql_query("UPDATE `user` SET `rating` = `rating` + '20' WHERE `id`='$user[id]' ");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." +20 рейтинга', '$time')");
msg('Поздравляем! Вы получили +20 рейтинга.');
} elseif($rand==3) {
mysql_query("UPDATE `user` SET `money` = `money` + '10' WHERE `id`='$user[id]' ");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." +10 монет', '$time')");
msg('Поздравляем! Вы получили +10 монет.');
} elseif($rand==4) {
mysql_query("UPDATE `user` SET `rating` = `rating` + '10' WHERE `id`='$user[id]' ");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." +10 рейтинга', '$time')");
msg('Поздравляем! Вы получили +10 рейтинга.');
} elseif($rand==5) {
mysql_query("UPDATE `user` SET `money` = `money` + '5' WHERE `id`='$user[id]' ");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." +5 монет', '$time')");
msg('Поздравляем! Вы получили 5 монет.');
} elseif($rand==6) {
mysql_query("UPDATE `user` SET `balls` = `balls` + '500' WHERE `id`='$user[id]' ");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." +500 баллов', '$time')");
msg('Поздравляем! Вы получили +500 баллов.');
} elseif($rand==7) {
mysql_query("INSERT INTO `gifts_user` (`id_user`, `id_ank`, `id_gift`, `coment`, `time`) values('1', '$user[id]', '1', 'Ежедневный подарок!', '$time')");
$id_gift = mysql_insert_id();
mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('1', '$user[id]', '$id_gift', 'new_gift', '$time')");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." бесплатный подарок', '$time')");
msg('Поздравляем! Вы получили бесплатный подарок.');
}  elseif($rand==8) {
mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`) values('$user[id]', '1', 'Ежедневный подарок!', '".($time+86400)."')");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." 1 день в лидерах', '$time')");
msg('Поздравляем! Вы получили 1 день в лидерах сайта.');
}  elseif($rand==9) {
mysql_query("UPDATE `user_set` SET `ocenka` = '".($time+86400)."' WHERE `id_user` = '$user[id]'");
mysql_query("INSERT INTO `gifts_free` (`id_user`, `type`, `time`) values('$user[id]', 'Получил".($user['pol'] == 1 ? '' : 'а')." 1 день оценку 5+', '$time')");
msg('Поздравляем! Вы получили 1 день день оценку 5+');
}
mysql_query("UPDATE `user` SET `time_gift` = '".($time+86400)."' WHERE `id`='$user[id]' ");
}
if($user['time_gift']>=$time)msg('Вы сегодня уже брали подарок, следующая возможно будет доступна завтра.');

echo "<div class='nav1'><a href='?gift'><img src='elka.png'></a></div>";
echo "<div class='mess'>Для получения подарка нажмите на ёлку.</div>";


echo "<div class='menu_razd'>Последние 3 призёра</div>";

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gifts_free` "),0);
if ($k_post==0) {
echo "   <div class='mess'>\n";
echo "Список пуст\n";
echo "  </div>\n";
}

$q = mysql_query("SELECT * FROM `gifts_free` ORDER BY `id` DESC LIMIT 3");
while ($post = mysql_fetch_assoc($q)) {
$ank=get_user($post['id_user']);

if ($num==0){echo '<div class="nav1">';$num=1;}
elseif ($num==1){echo '<div class="nav2">';$num=0;}

echo status($ank['id'])." ".group($ank['id'])."";
echo "<a href='/info.php?id=$ank[id]'>$ank[nick]</a>\n";
echo "".medal($ank['id'])." ".online($ank['id'])."</br>";

echo output_text($post['type']);

echo "</div>\n";
}


include_once '../../sys/inc/tfoot.php';
?>

